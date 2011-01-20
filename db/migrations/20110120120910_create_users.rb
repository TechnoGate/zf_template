class CreateUsers < ActiveRecord::Migration
  def self.up
    create_table :users do |t|
      t.string :login,           :null  => false, :limit => 30
      t.string :name,            :null  => false
      t.string :email,           :null  => false
      t.string :hashed_password, :null  => false
      t.string :phone,           :limit => 45
      t.string :profile_picture
      t.string :status,          :limit => 45
      t.boolean :blocked,        :null  => false, :default => false
      t.timestamp :last_login_at

      t.timestamps
    end

    add_index :users, :login, :unique => true
    add_index :users, :email, :unique => true
  end

  def self.down
    drop_table :users
  end
end