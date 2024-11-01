package logger

import (
	"github.com/dongwlin/upright-backend/internal/config"
	"go.uber.org/zap"
	"go.uber.org/zap/zapcore"
	"gopkg.in/natefinch/lumberjack.v2"
	"os"
)

func New(conf *config.Config) *zap.Logger {
	encoder := getEncoder(conf)
	level := getLevel(conf.Log.Level)
	writeSyncer := getWriteSyncer(conf)
	core := zapcore.NewCore(encoder, writeSyncer, level)
	options := getOptions(conf)
	logger := zap.New(core, options...)
	return logger
}

func getLevel(level string) zapcore.Level {
	switch level {
	case "debug":
		return zap.DebugLevel
	case "info":
		return zap.InfoLevel
	case "warn":
		return zap.WarnLevel
	case "error":
		return zap.ErrorLevel
	case "fatal":
		return zap.FatalLevel
	default:
		return zap.InfoLevel
	}
}

func getLumberjackLogger(conf *config.Config) lumberjack.Logger {
	return lumberjack.Logger{
		Filename:   conf.Log.Filename,
		MaxSize:    conf.Log.MaxSize,
		MaxBackups: conf.Log.MaxBackups,
		MaxAge:     conf.Log.MaxAge,
		Compress:   conf.Log.Compress,
	}
}

func getEncoder(conf *config.Config) zapcore.Encoder {
	encoderConfig := zap.NewProductionEncoderConfig()
	encoderConfig.EncodeTime = zapcore.ISO8601TimeEncoder
	return zapcore.NewJSONEncoder(encoderConfig)
}

func getWriteSyncer(conf *config.Config) zapcore.WriteSyncer {
	logger := getLumberjackLogger(conf)
	if conf.Env == "prod" {
		return zapcore.AddSync(&logger)
	}
	return zapcore.NewMultiWriteSyncer(
		zapcore.AddSync(os.Stdout),
		zapcore.AddSync(&logger),
	)
}

func getOptions(conf *config.Config) []zap.Option {
	if conf.Env == "prod" {
		return []zap.Option{
			zap.AddCaller(),
			zap.AddStacktrace(zap.ErrorLevel),
		}
	}
	return []zap.Option{
		zap.Development(),
		zap.AddCaller(),
		zap.AddStacktrace(zap.ErrorLevel),
	}
}
