package main

import (
	"fmt"
	"github.com/dongwlin/upright-backend/internal/server"
	"os"
)

func main() {
	httpServer := server.NewHttpServer()
	err := httpServer.Run(":8080")
	if err != nil {
		fmt.Println(err)
		os.Exit(0)
	}
}
