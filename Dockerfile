# builder
FROM golang:1.23 AS builder

WORKDIR /app

COPY go.mod ./
COPY go.sum ./

RUN go mod download

COPY . .

RUN export CGO_ENABLED=0; go build -v -o backend .

# runner
FROM scratch AS runner

COPY --from=builder /app/backend /

EXPOSE 8080

CMD [ "/backend", "serve"]