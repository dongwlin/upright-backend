package handler

import (
	"context"
	"github.com/dongwlin/upright-backend/internal/service"
	pasetoware "github.com/gofiber/contrib/paseto"
	"github.com/gofiber/fiber/v2"
	"strconv"
)

type UserHandler struct {
	userService *service.User
}

func RegisterUser(r fiber.Router, h *UserHandler) {
	r.Get("/users/me", h.GetByToken)
}

func NewUserHandler(userService *service.User) *UserHandler {
	return &UserHandler{
		userService: userService,
	}
}

type UserGetByTokenResponse struct {
}

func (h *UserHandler) GetByToken(c *fiber.Ctx) error {
	payload := c.Locals(pasetoware.DefaultContextKey).(string)
	id, err := strconv.Atoi(payload)
	if err != nil {
		return err
	}
	ctx := context.Background()
	user, err := h.userService.GetById(ctx, id)
	if err != nil {
		return err
	}
	return c.Status(fiber.StatusOK).JSON(user)
}
