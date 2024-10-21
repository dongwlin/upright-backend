package route

import (
	"github.com/dongwlin/upright-backend/internal/handler"
	"github.com/gofiber/fiber/v2"
)

func Setup(app *fiber.App) {
	app.All("/ping", handler.Ping)
}
