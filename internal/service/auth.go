package service

import (
	"context"
	"encoding/json"
	"fmt"
	"github.com/dongwlin/upright-backend/internal/config"
	"github.com/dongwlin/upright-backend/internal/ent"
	pasetoware "github.com/gofiber/contrib/paseto"
	"go.uber.org/zap"
	"io"
	"net/http"
	"time"
)

type Auth struct {
	logger      *zap.Logger
	conf        *config.Config
	userService *User
}

func NewAuth(logger *zap.Logger, conf *config.Config, userService *User) *Auth {
	return &Auth{
		logger:      logger,
		conf:        conf,
		userService: userService,
	}
}

type weAppCode2SessionResponse struct {
	SessionKey string `json:"session_key"`
	Unionid    string `json:"unionid"`
	Errmsg     string `json:"errmsg"`
	Openid     string `json:"openid"`
	Errcode    int    `json:"errcode"`
}

func (s *Auth) WeAppCode2Session(code string) (string, error) {
	s.logger.Info("Starting WeAppCode2Session", zap.String("code", code))

	url := fmt.Sprintf(
		"https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=%s",
		s.conf.Security.WeApp.AppID,
		s.conf.Security.WeApp.AppSecret,
		code,
		s.conf.Security.WeApp.GrantType,
	)

	resp, err := http.Get(url)
	if err != nil {
		s.logger.Error(
			"HTTP GET request failed",
			zap.String("url", url),
			zap.Error(err),
		)
		return "", err
	}
	defer resp.Body.Close()

	if resp.StatusCode != http.StatusOK {
		s.logger.Error(
			"Unexpected HTTP status",
			zap.String("HTTP status", resp.Status),
			zap.Int("status_code", resp.StatusCode),
		)
		return "", fmt.Errorf("unexpected HTTP status: %s", resp.Status)
	}

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		s.logger.Error("Failed to read response body", zap.Error(err))
		return "", fmt.Errorf("failed to read response body: %w", err)
	}

	var result weAppCode2SessionResponse
	if err = json.Unmarshal(body, &result); err != nil {
		s.logger.Error("Failed to unmarshal JSON", zap.Error(err))
		return "", fmt.Errorf("failed to unmarshal JSON: %w", err)
	}

	if result.Errcode != 0 {
		s.logger.Error(
			"Failed to get WeChat session",
			zap.Int("errcode", result.Errcode),
			zap.String("errmsg", result.Errmsg),
		)
		return "", fmt.Errorf("failed to get WeChat session: %v", result.Errcode)
	}

	ctx := context.Background()
	u, err := s.userService.GetByOpenid(ctx, result.Openid)
	if err != nil && ent.IsNotFound(err) {
		s.logger.Info("User not found, creating new user", zap.String("openid", result.Openid))
		params := CreateUserParams{
			Openid:   result.Openid,
			Nickname: "微信用户",
			Gender:   -1,
			Avatar:   "/static/images/avatar.jpg",
		}
		u, err = s.userService.Create(ctx, params)
		if err != nil {
			s.logger.Error("Failed to create user", zap.Error(err))
			return "", fmt.Errorf("failed to create user: %w", err)
		}
	} else {
		s.logger.Error("Failed to get user by openid", zap.Error(err))
		return "", fmt.Errorf("failed to get user by openid: %w", err)
	}

	token, err := pasetoware.CreateToken(
		[]byte(s.conf.Security.Paseto.Key),
		fmt.Sprintf("%d", u.ID),
		12*time.Hour,
		pasetoware.PurposeLocal,
	)
	if err != nil {
		s.logger.Error("Failed to create token", zap.Error(err))
		return "", fmt.Errorf("failed to create token: %w", err)
	}

	s.logger.Info("Token created successfully", zap.String("code", code))
	return token, nil
}
