require 'rubygems'
require 'bundler'
begin
  Bundler.setup(:default, :development)
rescue Bundler::BundlerError => e
  $stderr.puts e.message
  $stderr.puts "Run `bundle install` to install missing gems"
  exit e.status_code
end
require 'rake'

# Migrations
begin
  require 'active_record'
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
  puts "Please run 'bundle install', #{e}"
end

# Config generator
begin
  require 'parseconfig'
  require 'yaml'

  desc "Prepare config file read by standalone_migrations"
  task :prepare_config do
    puts "Preparing the config file from application.ini"

    adapter_mapping = {
      'pgsql' => 'postgresql'
    }

    application_ini = File.expand_path(File.dirname(__FILE__)+'/application/configs/application.ini')
    raise "application.ini cannot be found please copy it from application.ini.sample" unless File.exist? application_ini

    config_ini = TechnoGate::ParseConfig.new(application_ini)
    config_yml = File.open(File.expand_path(File.dirname(__FILE__)+'/db/config.yml'), 'w')

    db_config = Hash.new
    ['production', 'development'].each do |env|
      if config_ini.params.include? env
        adapter = config_ini.params[env]["resources.activerecord.connections.#{env}.dsn.adapter"]
        if adapter_mapping.has_key? adapter
          adapter = adapter_mapping[adapter]
        end
        db_config[env] = {
          'adapter'  => adapter,
          'hostname' => config_ini.params[env]["resources.activerecord.connections.#{env}.dsn.hostspec"],
          'port'		 => config_ini.params[env]["resources.activerecord.connections.#{env}.dsn.port"].to_i,
          'username' => config_ini.params[env]["resources.activerecord.connections.#{env}.dsn.user"],
          'password' => config_ini.params[env]["resources.activerecord.connections.#{env}.dsn.pass"],
          'database' => config_ini.params[env]["resources.activerecord.connections.#{env}.dsn.database"],
        }
      end
    end

    config_yml.write(YAML::dump(db_config))
  end
rescue LoadError => e
  puts "Please run 'bundle install', #{e}"
end