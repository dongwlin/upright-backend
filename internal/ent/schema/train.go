package schema

import (
	"time"

	"entgo.io/ent"
	"entgo.io/ent/schema/edge"
	"entgo.io/ent/schema/field"
)

// Train holds the schema definition for the Train entity.
type Train struct {
	ent.Schema
}

// Fields of the Train.
func (Train) Fields() []ent.Field {
	return []ent.Field{
		field.Time("start_time"),
		field.Time("end_time"),
		field.Int64("bend_down_times"),
		field.Int64("hunch_down_times"),
		field.Int64("ill_keep_times"),
		field.String("description"),
		field.Int8("status"),
		field.Time("created_at").
			Default(time.Now()),
	}
}

// Edges of the Train.
func (Train) Edges() []ent.Edge {
	return []ent.Edge{
		edge.From("owner", User.Type).
			Ref("trains").
			Unique(),
	}
}
