package service

import (
	"context"
	"github.com/dongwlin/upright-backend/internal/ent"
	"github.com/dongwlin/upright-backend/internal/ent/user"
)

type User struct {
	db *ent.Client
}

func NewUser(db *ent.Client) *User {
	return &User{
		db: db,
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
	u, err := s.db.User.Create().
		SetOpenid(params.Openid).
		SetNickname(params.Nickname).
		SetGender(params.Gender).
		SetAvatar(params.Avatar).
		SetDescription(params.Description).
		SetStatus(params.Status).
		Save(ctx)
	return u, err
}

func (s *User) GetByOpenid(ctx context.Context, openid string) (*ent.User, error) {
	u, err := s.db.User.Query().
		Where(user.OpenidEQ(openid)).
		First(ctx)
	return u, err
}

func (s *User) GetById(ctx context.Context, id int) (*ent.User, error) {
	u, err := s.db.User.Get(ctx, id)
	return u, err
}
