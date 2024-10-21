package schema

import (
	"time"

	"entgo.io/ent"
	"entgo.io/ent/schema/edge"
	"entgo.io/ent/schema/field"
)

// User holds the schema definition for the User entity.
type User struct {
	ent.Schema
}

// Fields of the User.
func (User) Fields() []ent.Field {
	return []ent.Field{
		field.String("openid").
			Unique(),
		field.String("nickname"),
		field.Int8("gender").
			Default(-1),
		field.String("avatar"),
		field.String("description"),
		field.Int8("status"),
		field.Time("created_at").
			Default(time.Now()),
	}
}

// Edges of the User.
func (User) Edges() []ent.Edge {
	return []ent.Edge{
		edge.To("trains", Train.Type),
	}
}
