package handler

import (
	"github.com/dongwlin/upright-backend/internal/service"
	"github.com/gofiber/fiber/v2"
)

type AuthHandler struct {
	authService *service.Auth
}

func RegisterUserHandler(r fiber.Router, h *AuthHandler) {
	r.Post("/auth/login/weapp", h.WeAppLogin)
}

func NewAuthHandler(authService *service.Auth) *AuthHandler {
	return &AuthHandler{}
}

type WeAppLoginRequest struct {
	Code string `json:"code"`
}

func (h *AuthHandler) WeAppLogin(c *fiber.Ctx) error {
	req := &WeAppLoginRequest{}
	if err := c.BodyParser(req); err != nil {
		return fiber.ErrBadRequest
	}
	token, err := h.authService.WeAppCode2Session(req.Code)
	if err != nil {
		return err
	}
	return c.Status(fiber.StatusOK).JSON(fiber.Map{"token": token})
}
