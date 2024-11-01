//go:build wireinject
// +build wireinject

package wire

import (
	"github.com/dongwlin/upright-backend/internal/config"
	"github.com/dongwlin/upright-backend/internal/ent"
	"github.com/dongwlin/upright-backend/internal/handler"
	"github.com/dongwlin/upright-backend/internal/server"
	"github.com/dongwlin/upright-backend/internal/service"
	"github.com/google/wire"
	"go.uber.org/zap"
)

var serviceSet = wire.NewSet(
	service.NewAuth,
	service.NewUser,
)

var handlerSet = wire.NewSet(
	handler.NewPingHandler,
	handler.NewAuthHandler,
	handler.NewUserHandler,
)

func NewHttpServer(conf *config.Config, logger *zap.Logger, db *ent.Client) *server.HttpServer {
	wire.Build(
		serviceSet,
		handlerSet,
		server.NewHttpServer,
	)
	return nil
}
