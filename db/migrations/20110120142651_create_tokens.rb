class CreateTokens < ActiveRecord::Migration
  def self.up
    create_table :tokens do |t|
      t.string :hash,        :null => false, :limit => 40
      t.integer :thing_id,   :null => false
      t.string  :thing_type, :null => false, :limit => 16
      t.string  :action
      t.integer :used
      t.boolean :auto_delete
      t.integer :status,                     :limit => 1

      t.timestamps
    end

    add_index :tokens, :hash, :unique => true
  end

  def self.down
    drop_table :tokens
  end
end