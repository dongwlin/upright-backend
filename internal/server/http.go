package server

import (
	"github.com/dongwlin/upright-backend/internal/route"
	"github.com/gofiber/fiber/v2"
)

type HttpServer struct {
	app *fiber.App
}

func NewHttpServer() *HttpServer {
	app := fiber.New()
	route.Setup(app)
	return &HttpServer{
		app: app,
	}
}

func (s *HttpServer) Run(addr string) error {
	return s.app.Listen(addr)
}
