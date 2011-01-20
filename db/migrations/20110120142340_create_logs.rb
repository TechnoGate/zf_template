class CreateLogs < ActiveRecord::Migration
  def self.up
    create_table :logs do |t|
      t.references :user
      t.string :session_cookie
      t.string :level, :null => false, :limit => 128
      t.string :ip,                    :limit => 20
      t.string :name,  :null => false, :limit => 128
      t.string :p1
      t.string :p2
      t.string :p3
      t.string :p4

      t.timestamps
    end
  end

  def self.down
    drop_table :logs
  end
end