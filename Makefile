.PHONY: build
build:
	go build -o ./bin/upright-backend main.go

.PHONY: docker
TAG ?= dev
docker:
	docker build -t upright-backend:$(TAG) .

.PHONY: ent
ent:
	go generate ./internal/ent
