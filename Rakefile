# encoding: utf-8
# frozen_string_literal: true


task :default => %w[init]
task :init => %w[db:init assets:install deps:install]

namespace 'db' do

  task :init do
    unless File.exist? 'db/horo-db.sqlite3'
      sh 'sqlite3 db/horo-db.sqlite3 < db/init.sql'
    end
  end

  task :drop do
    if File.exist? 'db/horo-db.sqlite3'
      sh 'rm db/horo-db.sqlite3'
    end
  end

end

namespace 'assets' do

  task :install do
    sh "bower install"
  end

end

namespace 'deps' do

  task :install do
    sh 'composer install'
  end

end
