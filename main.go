package main

import (
	"fmt"
	"github.com/dongwlin/upright-backend/internal/config"
	"github.com/dongwlin/upright-backend/internal/ent"
	"github.com/dongwlin/upright-backend/internal/logger"
	"github.com/dongwlin/upright-backend/internal/wire"
	"go.uber.org/zap"
	"os"
)

func main() {
	conf := config.New()
	l := logger.New(conf)
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
		l.Fatal("Failed opening connection to postgres", zap.Error(err))
	}
	httpServer := wire.NewHttpServer(conf, l, db)
	err = httpServer.Run(":8080")
	if err != nil {
		os.Exit(1)
	}
}
