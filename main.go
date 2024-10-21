package main

import "github.com/dongwlin/upright-backend/internal/server"

func main() {
	server := server.NewHttpServer()
	server.Run(":8080")
}
