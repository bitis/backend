@servers(['prod' => ['root@122.51.226.83']])

@setup
    $repository = 'https://gitlab+deploy-token-11575:42TzRxqvzBQViYDewmYh@jihulab.com/yuguaikeji/xinglanapp.git';
    $releases_dir = '/www/app/releases';
    $app_dir = '/www/wwwroot/xinglan';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    run_composer
    update_symlinks
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    export COMPOSER_ALLOW_SUPERUSER=1
    composer install --prefer-dist --no-scripts -q -o
    chown -R www:www {{ $new_release_dir }}
@endtask

@task('update_symlinks')
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
    service php-fpm-81 restart
@endtask

@task('restart-queues', ['on' => 'prod'])
    cd $releases_dir
    php artisan queue:restart
@endtask
