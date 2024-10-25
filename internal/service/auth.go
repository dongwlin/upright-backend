package service

import (
	"context"
	"encoding/json"
	"fmt"
	"github.com/dongwlin/upright-backend/internal/config"
	"github.com/dongwlin/upright-backend/internal/ent"
	pasetoware "github.com/gofiber/contrib/paseto"
	"io"
	"net/http"
	"time"
)

type Auth struct {
	conf        *config.Config
	userService *User
}

func NewAuth(conf *config.Config, userService *User) *Auth {
	return &Auth{
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
	url := fmt.Sprintf(
		"https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=%s",
		s.conf.Security.WeApp.AppID,
		s.conf.Security.WeApp.AppSecret,
		code,
		s.conf.Security.WeApp.GrantType,
	)

	resp, err := http.Get(url)
	if err != nil {
		return "", err
	}
	defer resp.Body.Close()

	if resp.StatusCode != http.StatusOK {
		return "", fmt.Errorf("unexpected HTTP status: %s", resp.Status)
	}

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return "", fmt.Errorf("failed to read response body: %w", err)
	}

	var result weAppCode2SessionResponse
	if err = json.Unmarshal(body, &result); err != nil {
		return "", fmt.Errorf("failed to unmarshal JSON: %w", err)
	}

	if result.Errcode != 0 {
		return "", fmt.Errorf("WeChat API error: %v", result.Errcode)
	}

	ctx := context.Background()
	u, err := s.userService.GetByOpenid(ctx, result.Openid)
	if err != nil && ent.IsNotFound(err) {
		params := CreateUserParams{
			Openid:   result.Openid,
			Nickname: "微信用户",
			Gender:   -1,
			Avatar:   "/static/images/avatar.jpg",
		}
		u, err = s.userService.Create(ctx, params)
		if err != nil {
			return "", fmt.Errorf("failed to create user: %w", err)
		}
	} else {
		return "", fmt.Errorf("failed to get user by openid: %w", err)
	}
	token, err := pasetoware.CreateToken(
		[]byte(s.conf.Security.Paseto.Key),
		fmt.Sprintf("%d", u.ID),
		12*time.Hour,
		pasetoware.PurposeLocal,
	)
	if err != nil {
		return "", fmt.Errorf("failed to create token: %w", err)
	}
	return token, nil
}
