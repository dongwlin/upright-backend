package service

import (
	"encoding/json"
	"fmt"
	"net/http"

	"github.com/dongwlin/upright-backend/internal/ent"
)

type User interface {
	Login() bool
}

type WXUser struct {
	store *ent.Client
	Code  string
}

type wxJScode2SessionResponse struct {
	OpenID     string `json:"openid"`
	SessionKey string `json:"session_key"`
	Error      string `json:"errcode"`
	ErrorMsg   string `json:"errmsg"`
}

func (u *WXUser) Login() bool {
	appId := ""
	appSecret := ""
	grantType := ""

	url := "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=%s"
	url = fmt.Sprintf(url, appId, appSecret, u.Code, grantType)
	resp, err := http.Get(url)
	if err != nil {
		// todo: log the err
		return false
	}

	defer resp.Body.Close()

	if resp.StatusCode != http.StatusOK {
		// todo: log the err
		return false
	}

	var wxResp wxJScode2SessionResponse
	if err := json.NewDecoder(resp.Body).Decode(&wxResp); err != nil {
		// todo: log the err
		return false
	}

	if wxResp.Error != "" {
		// todo: log the err
		return false
	}

	return false
}

func (u *WXUser) create() {

}
