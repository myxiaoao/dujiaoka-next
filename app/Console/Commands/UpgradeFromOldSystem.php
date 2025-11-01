<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Exception;

class UpgradeFromOldSystem extends Command
{
    /**
     * 命令签名
     *
     * @var string
     */
    protected $signature = 'dujiaoka:upgrade
                            {--host= : 老数据库主机地址}
                            {--port=3306 : 老数据库端口}
                            {--database= : 老数据库名称}
                            {--username= : 老数据库用户名}
                            {--password= : 老数据库密码}
                            {--old-path= : 老系统文件路径}
                            {--skip-files : 跳过文件复制}
                            {--dry-run : 仅验证连接，不执行实际操作}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '从独角数卡 Laravel 6 升级数据到 Laravel 12 新系统';

    /**
     * 需要迁移的表
     *
     * @var array
     */
    protected $tables = [
        'goods_group',
        'goods',
        'carmis',
        'coupons',
        'coupons_goods',
        'emailtpls',
        'pays',
        'orders',
    ];

    /**
     * 老数据库连接配置
     *
     * @var array
     */
    protected $oldDbConfig = [];

    /**
     * 执行命令
     */
    public function handle()
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════════════════╗');
        $this->info('║       独角数卡 Laravel 6 → Laravel 12 升级工具              ║');
        $this->info('╚══════════════════════════════════════════════════════════════╝');
        $this->info('');

        // 步骤 1: 获取老数据库配置
        if (!$this->getOldDatabaseConfig()) {
            return Command::FAILURE;
        }

        // 步骤 2: 验证连接
        if (!$this->validateConnections()) {
            return Command::FAILURE;
        }

        // 如果是 dry-run，到这里就结束
        if ($this->option('dry-run')) {
            $this->info('');
            $this->info('✓ Dry-run 模式：所有连接验证通过！');
            return Command::SUCCESS;
        }

        // 步骤 3: 显示数据统计并确认
        if (!$this->showDataStatistics()) {
            return Command::FAILURE;
        }

        // 步骤 4: 备份当前新数据库
        if (!$this->backupNewDatabase()) {
            return Command::FAILURE;
        }

        // 步骤 5: 迁移数据
        if (!$this->migrateData()) {
            return Command::FAILURE;
        }

        // 步骤 6: 复制文件资产
        if (!$this->option('skip-files')) {
            $this->copyFileAssets();
        }

        // 步骤 7: 验证数据
        if (!$this->validateData()) {
            return Command::FAILURE;
        }

        // 完成
        $this->displaySuccessMessage();

        return Command::SUCCESS;
    }

    /**
     * 获取老数据库配置
     */
    protected function getOldDatabaseConfig(): bool
    {
        $this->info('📋 步骤 1/7: 配置老数据库连接');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $this->oldDbConfig = [
            'driver' => 'mysql',
            'host' => $this->option('host') ?? $this->ask('老数据库主机地址', '127.0.0.1'),
            'port' => $this->option('port') ?? $this->ask('老数据库端口', '3306'),
            'database' => $this->option('database') ?? $this->ask('老数据库名称', 'dujiaoka'),
            'username' => $this->option('username') ?? $this->ask('老数据库用户名', 'root'),
            'password' => $this->option('password') ?? $this->secret('老数据库密码'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ];

        $this->info('');
        $this->table(
            ['配置项', '值'],
            [
                ['主机', $this->oldDbConfig['host']],
                ['端口', $this->oldDbConfig['port']],
                ['数据库', $this->oldDbConfig['database']],
                ['用户名', $this->oldDbConfig['username']],
                ['密码', str_repeat('*', strlen($this->oldDbConfig['password']))],
            ]
        );

        return true;
    }

    /**
     * 验证数据库连接
     */
    protected function validateConnections(): bool
    {
        $this->info('');
        $this->info('🔍 步骤 2/7: 验证数据库连接');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // 配置老数据库连接
        config(['database.connections.old_system' => $this->oldDbConfig]);

        // 测试老数据库连接
        $this->info('正在测试老数据库连接...');
        try {
            $oldConnection = DB::connection('old_system');
            $oldConnection->getPdo();
            $this->info('✓ 老数据库连接成功');
        } catch (Exception $e) {
            $this->error('✗ 老数据库连接失败: ' . $e->getMessage());
            return false;
        }

        // 测试新数据库连接
        $this->info('正在测试新数据库连接...');
        try {
            DB::connection()->getPdo();
            $this->info('✓ 新数据库连接成功');
        } catch (Exception $e) {
            $this->error('✗ 新数据库连接失败: ' . $e->getMessage());
            return false;
        }

        // 检查老数据库中的表是否存在
        $this->info('正在检查老数据库表结构...');
        $missingTables = [];
        foreach ($this->tables as $table) {
            if (!$oldConnection->getSchemaBuilder()->hasTable($table)) {
                $missingTables[] = $table;
            }
        }

        if (!empty($missingTables)) {
            $this->error('✗ 老数据库中缺少以下表: ' . implode(', ', $missingTables));
            return false;
        }

        $this->info('✓ 所有必需的表都存在');

        return true;
    }

    /**
     * 显示数据统计
     */
    protected function showDataStatistics(): bool
    {
        $this->info('');
        $this->info('📊 步骤 3/7: 数据统计');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $oldConnection = DB::connection('old_system');
        $statistics = [];

        foreach ($this->tables as $table) {
            $count = $oldConnection->table($table)->count();
            $statistics[] = [$table, number_format($count), '条记录'];
        }

        $this->table(['数据表', '数量', ''], $statistics);

        $this->warn('');
        $this->warn('⚠️  重要提示:');
        $this->warn('  • 此操作将从老数据库复制数据到当前新数据库');
        $this->warn('  • 老数据库不会被修改（只读操作）');
        $this->warn('  • 新数据库中的现有数据将被替换');
        $this->warn('  • 操作前会自动备份新数据库');
        $this->info('');

        return $this->confirm('确认开始数据迁移吗？', false);
    }

    /**
     * 备份新数据库
     */
    protected function backupNewDatabase(): bool
    {
        $this->info('');
        $this->info('💾 步骤 4/7: 备份当前新数据库');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_His') . '.sql';
        $filepath = $backupPath . '/' . $filename;

        $dbConfig = config('database.connections.' . config('database.default'));

        $command = sprintf(
            'mysqldump -h%s -P%s -u%s -p%s %s > %s',
            $dbConfig['host'],
            $dbConfig['port'] ?? 3306,
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['database'],
            $filepath
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0 && File::exists($filepath)) {
            $size = File::size($filepath);
            $this->info('✓ 备份完成: ' . $filename . ' (' . $this->formatBytes($size) . ')');
            return true;
        } else {
            $this->warn('⚠️  备份失败，但仍可继续（不推荐）');
            return $this->confirm('是否继续？', false);
        }
    }

    /**
     * 迁移数据
     */
    protected function migrateData(): bool
    {
        $this->info('');
        $this->info('🚀 步骤 5/7: 开始数据迁移');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $oldConnection = DB::connection('old_system');
        $newConnection = DB::connection();

        $progressBar = $this->output->createProgressBar(count($this->tables));
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

        foreach ($this->tables as $table) {
            $progressBar->setMessage("正在迁移 {$table}...");

            try {
                // 开始事务
                $newConnection->beginTransaction();

                // 清空新表
                $newConnection->table($table)->truncate();

                // 分批复制数据
                $oldConnection->table($table)->orderBy('id')->chunk(500, function ($records) use ($table, $newConnection) {
                    $data = json_decode(json_encode($records), true);
                    $newConnection->table($table)->insert($data);
                });

                // 提交事务
                $newConnection->commit();

                $progressBar->advance();

            } catch (Exception $e) {
                $newConnection->rollBack();
                $this->error('');
                $this->error('✗ 迁移表 ' . $table . ' 失败: ' . $e->getMessage());
                return false;
            }
        }

        $progressBar->finish();
        $this->info('');
        $this->info('✓ 数据迁移完成');

        return true;
    }

    /**
     * 复制文件资产
     */
    protected function copyFileAssets(): void
    {
        $this->info('');
        $this->info('📁 步骤 6/7: 复制文件资产');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $oldPath = $this->option('old-path');

        if (!$oldPath) {
            $oldPath = $this->ask('请输入老系统的完整路径（如 /var/www/dujiaoka）');
        }

        if (!$oldPath || !File::exists($oldPath)) {
            $this->warn('⚠️  老系统路径不存在，跳过文件复制');
            return;
        }

        // 复制 storage/app 目录
        $oldStoragePath = $oldPath . '/storage/app';
        $newStoragePath = storage_path('app');

        if (File::exists($oldStoragePath)) {
            $this->info('正在复制 storage/app 目录...');
            File::copyDirectory($oldStoragePath, $newStoragePath);
            $this->info('✓ storage/app 复制完成');
        }

        // 复制 public/uploads 目录（如果存在）
        $oldUploadsPath = $oldPath . '/public/uploads';
        $newUploadsPath = public_path('uploads');

        if (File::exists($oldUploadsPath)) {
            $this->info('正在复制 public/uploads 目录...');
            File::copyDirectory($oldUploadsPath, $newUploadsPath);
            $this->info('✓ public/uploads 复制完成');
        }

        $this->info('✓ 文件资产复制完成');
    }

    /**
     * 验证数据
     */
    protected function validateData(): bool
    {
        $this->info('');
        $this->info('✅ 步骤 7/7: 验证数据完整性');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $oldConnection = DB::connection('old_system');
        $newConnection = DB::connection();

        $validation = [];
        $hasError = false;

        foreach ($this->tables as $table) {
            $oldCount = $oldConnection->table($table)->count();
            $newCount = $newConnection->table($table)->count();
            $match = $oldCount === $newCount;

            $validation[] = [
                $table,
                number_format($oldCount),
                number_format($newCount),
                $match ? '✓' : '✗',
            ];

            if (!$match) {
                $hasError = true;
            }
        }

        $this->table(['数据表', '老库数量', '新库数量', '状态'], $validation);

        if ($hasError) {
            $this->error('');
            $this->error('✗ 数据验证失败：数量不匹配');
            return false;
        }

        $this->info('');
        $this->info('✓ 数据验证通过：所有数据已正确迁移');

        return true;
    }

    /**
     * 显示成功消息
     */
    protected function displaySuccessMessage(): void
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════════════════╗');
        $this->info('║                  ✓ 升级完成！                                ║');
        $this->info('╚══════════════════════════════════════════════════════════════╝');
        $this->info('');
        $this->info('下一步操作：');
        $this->info('  1. 创建 Filament 管理员账号：');
        $this->info('     php artisan make:filament-user');
        $this->info('');
        $this->info('  2. 清理缓存：');
        $this->info('     php artisan cache:clear');
        $this->info('     php artisan config:cache');
        $this->info('     php artisan route:cache');
        $this->info('');
        $this->info('  3. 访问后台：');
        $this->info('     http://your-domain.com/admin');
        $this->info('');
        $this->info('  4. 测试功能：');
        $this->info('     • 前台商品浏览');
        $this->info('     • 下单流程');
        $this->info('     • 支付网关');
        $this->info('     • 后台管理');
        $this->info('');
        $this->warn('⚠️  重要提醒：');
        $this->warn('  • 老数据库未被修改，可随时回滚');
        $this->warn('  • 建议保留老系统1周作为备份');
        $this->warn('  • 充分测试后再切换正式环境');
        $this->info('');
    }

    /**
     * 格式化字节大小
     */
    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
