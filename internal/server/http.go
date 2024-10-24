package server

import (
	"fmt"
	"github.com/dongwlin/upright-backend/internal/config"
	"github.com/dongwlin/upright-backend/internal/ent"
	"github.com/dongwlin/upright-backend/internal/handler"
	"github.com/dongwlin/upright-backend/internal/service"
	"github.com/gofiber/fiber/v2"
	_ "github.com/lib/pq"
	"log"
)

type HttpServer struct {
	app *fiber.App
}

func NewHttpServer() *HttpServer {
	app := fiber.New()
	router := app.Group("/")
	pingHandler := handler.NewPingHandler()
	handler.RegisterPing(router, pingHandler)
	api := app.Group("/api")

	conf := config.New()
	db, err := ent.Open(
		"postgres",
		fmt.Sprintf(
			"host=%s port=%d user=%s password=%s dbname=%s sslmode=disable",
			conf.Database.Host,
			conf.Database.Port,
			conf.Database.User,
			conf.Database.Password,
			conf.Database.DBName,
		),
	)
	if err != nil {
		log.Fatal(err)
	}
	userService := service.NewUser(db)
	authService := service.NewAuth(conf, userService)
	authHandler := handler.NewAuthHandler(authService)
	handler.RegisterUserHandler(api, authHandler)
	app.Static("/static", "./static")
	return &HttpServer{
		app: app,
	}
}

func (s *HttpServer) Run(addr string) error {
	return s.app.Listen(addr)
}
