class CreateEvents < ActiveRecord::Migration
  def self.up
    create_table :events do |t|
      t.integer :thing_id,   :null => false
      t.string  :thing_type, :null => false, :limit => 16
      t.string  :name,                       :limit => 45
      t.string  :type,                       :limit => 45
      t.column  :parent_id, :integer, :references => :event
      t.text    :parameters


      t.timestamps
    end
  end

  def self.down
    drop_table :events
  end
end