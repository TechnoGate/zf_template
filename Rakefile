# Migrations
begin
  require 'tasks/standalone_migrations'
  MigratorTasks.new do |t|
    t.migrations = "db/migrations"
    t.config = "db/config.yml"
    t.schema = "db/schema.rb"
    # t.env = "DB"
    t.default_env = "development"
    # t.verbose = true
    # t.log_level = Logger::ERROR
  end
rescue LoadError => e
  puts "standalone_migrations is not installed, please run 'bundle install'"
end

# Config generator
begin
  require 'parseconfig'
  require 'yaml'

  desc "Prepare config file read by standalone_migrations"
  task :prepare_config do
    puts "Preparing the config file from application.ini"

    application_ini = File.expand_path(File.dirname(__FILE__)+'/application/configs/application.ini')
    raise "application.ini cannot be found please copy it from application.ini.sample" unless File.exist? application_ini

    config_ini = ParseConfig.new(application_ini)
    config_yml = File.open(File.expand_path(File.dirname(__FILE__)+'/db/config.yml'), 'w')

    db_config = Hash.new
    ['production', 'development'].each do |env|
      break unless config_ini.params.include? env
      db_config[env] = {
        'adapter'  => config_ini.params[env]['resources.activerecord.connections.production.dsn.adapter'],
        'hostname' => config_ini.params[env]['resources.activerecord.connections.production.dsn.hostspec'],
        'username' => config_ini.params[env]['resources.activerecord.connections.production.dsn.user'],
        'password' => config_ini.params[env]['resources.activerecord.connections.production.dsn.pass'],
        'database' => config_ini.params[env]['resources.activerecord.connections.production.dsn.database'],
      }
    end

    config_yml.write(YAML::dump(db_config))
  end
rescue LoadError => e
  puts "parseconfig is not installed, please run 'bundle install'"
end