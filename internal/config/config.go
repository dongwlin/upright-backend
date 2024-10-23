package config

import (
	"github.com/spf13/viper"
	"log"
)

type Config struct {
	Http     HttpConfig
	Database DatabaseConfig
	Security SecurityConfig
}

type HttpConfig struct {
	Host string
	Port int
}

type DatabaseConfig struct {
	Host     string
	Port     int
	User     string
	Password string
	DBName   string
}

type SecurityConfig struct {
	Paseto PasetoConfig
	WeApp  WeAppConfig `mapstructure:"we_app"`
}

type PasetoConfig struct {
	Key string
}

type WeAppConfig struct {
	AppID     string `mapstructure:"app_id"`
	AppSecret string `mapstructure:"app_secret"`
	GrantType string `mapstructure:"grant_type"`
}

func New() *Config {
	v := viper.New()

	// Set default values
	v.SetDefault("http.host", "localhost")
	v.SetDefault("http.port", 8080)

	// Set the file name of the configurations file
	v.SetConfigName("config")
	v.SetConfigType("yaml")

	// Add the path to look for the configurations file
	v.AddConfigPath(".")

	// Read the configuration file
	if err := v.ReadInConfig(); err != nil {
		log.Fatalf("Error reading config file, %s", err)
	}

	var config Config

	// Unmarshal the configuration into the Config struct
	if err := v.Unmarshal(&config); err != nil {
		log.Fatalf("Unable to decode into struct, %v", err)
	}

	return &config
}
