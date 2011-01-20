# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended to check this file into your version control system.

ActiveRecord::Schema.define(:version => 20110120142340) do

  create_table "debugs", :force => true do |t|
    t.string   "type",       :null => false
    t.text     "desc"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "events", :force => true do |t|
    t.integer  "thing_id",                 :null => false
    t.string   "thing_type", :limit => 16, :null => false
    t.string   "name",       :limit => 45
    t.string   "type",       :limit => 45
    t.integer  "parent_id"
    t.text     "parameters"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "logs", :force => true do |t|
    t.integer  "user_id"
    t.string   "session_cookie"
    t.string   "level",          :limit => 128, :null => false
    t.string   "ip",             :limit => 20
    t.string   "name",           :limit => 128, :null => false
    t.string   "p1"
    t.string   "p2"
    t.string   "p3"
    t.string   "p4"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "users", :force => true do |t|
    t.string   "login",           :limit => 30,                    :null => false
    t.string   "name",                                             :null => false
    t.string   "email",                                            :null => false
    t.string   "hashed_password",                                  :null => false
    t.string   "phone",           :limit => 45
    t.string   "profile_picture"
    t.string   "status",          :limit => 45
    t.boolean  "blocked",                       :default => false, :null => false
    t.datetime "last_login_at"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "users", ["email"], :name => "index_users_on_email", :unique => true
  add_index "users", ["login"], :name => "index_users_on_login", :unique => true

end
