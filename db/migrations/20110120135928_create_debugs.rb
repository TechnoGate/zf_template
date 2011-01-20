class CreateDebugs < ActiveRecord::Migration
  def self.up
    create_table :debugs do |t|
      t.string :type, :null => false
      t.text   :desc

      t.timestamps
    end
  end

  def self.down
    drop_table :debugs
  end
end