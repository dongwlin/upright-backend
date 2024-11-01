package service

import (
	"context"
	"github.com/dongwlin/upright-backend/internal/ent"
	"github.com/dongwlin/upright-backend/internal/ent/user"
	"go.uber.org/zap"
)

type User struct {
	logger *zap.Logger
	db     *ent.Client
}

func NewUser(logger *zap.Logger, db *ent.Client) *User {
	return &User{
		logger: logger,
		db:     db,
	}
}

type CreateUserParams struct {
	Openid      string
	Nickname    string
	Gender      int8
	Avatar      string
	Description string
	Status      int8
}

func (s *User) Create(ctx context.Context, params CreateUserParams) (*ent.User, error) {
	s.logger.Info(
		"Creating user",
		zap.String("openid", params.Openid),
		zap.String("nickname", params.Nickname),
	)

	u, err := s.db.User.Create().
		SetOpenid(params.Openid).
		SetNickname(params.Nickname).
		SetGender(params.Gender).
		SetAvatar(params.Avatar).
		SetDescription(params.Description).
		SetStatus(params.Status).
		Save(ctx)
	if err != nil {
		s.logger.Error("Failed to create user", zap.Error(err))
		return nil, err
	}

	s.logger.Info("User created successfully", zap.Int("user_id", u.ID))
	return u, nil
}

func (s *User) GetByOpenid(ctx context.Context, openid string) (*ent.User, error) {
	s.logger.Info("Getting user by openid", zap.String("openid", openid))

	u, err := s.db.User.Query().
		Where(user.OpenidEQ(openid)).
		First(ctx)
	if err != nil {
		s.logger.Error("Failed to get user by openid", zap.Error(err))
		return nil, err
	}

	s.logger.Info("User retrieved successfully", zap.Int("user_id", u.ID))
	return u, nil
}

func (s *User) GetById(ctx context.Context, id int) (*ent.User, error) {
	s.logger.Info("Getting user by ID", zap.Int("user_id", id))

	u, err := s.db.User.Get(ctx, id)
	if err != nil {
		s.logger.Error("Failed to get user by ID", zap.Error(err))
		return nil, err
	}

	s.logger.Info("User retrieved successfully", zap.Int("user_id", u.ID))
	return u, nil
}
