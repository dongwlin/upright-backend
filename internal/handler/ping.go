package handler

import "github.com/gofiber/fiber/v2"

type PingHandler struct {
}

func RegisterPing(r fiber.Router, h *PingHandler) {
	r.All("/ping", h.Ping)
}

func NewPingHandler() *PingHandler {
	return &PingHandler{}
}

func (h *PingHandler) Ping(c *fiber.Ctx) error {
	return c.Status(fiber.StatusOK).SendString("Pong!!!!!")
}
