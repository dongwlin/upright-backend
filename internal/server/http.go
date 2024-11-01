package server

import (
	"github.com/dongwlin/upright-backend/internal/config"
	"github.com/dongwlin/upright-backend/internal/handler"
	"github.com/gofiber/contrib/fiberzap/v2"
	pasetoware "github.com/gofiber/contrib/paseto"
	"github.com/gofiber/fiber/v2"
	_ "github.com/lib/pq"
	"go.uber.org/zap"
)

type HttpServer struct {
	logger *zap.Logger
	app    *fiber.App
}

func NewHttpServer(
	conf *config.Config,
	logger *zap.Logger,
	pingHandler *handler.PingHandler,
	authHandler *handler.AuthHandler,
	userHandler *handler.UserHandler,
) *HttpServer {
	app := fiber.New()

	app.Use(fiberzap.New(fiberzap.Config{
		Logger: logger,
	}))

	router := app.Group("/")
	handler.RegisterPing(router, pingHandler)

	api := app.Group("/api")
	handler.RegisterUserHandler(api, authHandler)

	app.Static("/static", "./static")

	auth := api.Group("/", pasetoware.New(pasetoware.Config{
		SymmetricKey: []byte(conf.Security.Paseto.Key),
		TokenPrefix:  "Bearer",
	}))
	handler.RegisterUser(auth, userHandler)

	return &HttpServer{
		logger: logger,
		app:    app,
	}
}

func (s *HttpServer) Run(addr string) error {
	s.logger.Info("Attempting to start http server", zap.String("addr", addr))

	if err := s.app.Listen(addr); err != nil {
		s.logger.Fatal("Failed to start http server", zap.Error(err))
		return err
	}

	s.logger.Info("Http server started successfully", zap.String("addr", addr))
	return nil
}
