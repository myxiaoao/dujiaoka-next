# 测试数据生成指南

本文档说明如何在开发环境中生成和管理测试数据。

## 🎯 功能特性

- ✅ **工厂模式生成** - 使用 Laravel Factory 生成真实的测试数据
- ✅ **业务场景覆盖** - 涵盖所有业务状态（待支付、已完成、失败等）
- ✅ **关联数据** - 自动建立商品、卡密、订单、优惠券之间的关联
- ✅ **保留系统数据** - 不影响邮件模板和支付方式配置
- ✅ **一键清空** - 方便上线前清理测试数据

---

## 📦 生成的数据

运行 `TestDataSeeder` 将生成以下测试数据：

| 数据类型 | 数量 | 说明 |
|---------|------|------|
| **商品分类** | 8个 | 游戏、软件、会员、云服务等分类 |
| **商品** | 30个 | - 20个自动发货商品<br>- 5个热门商品（高销量）<br>- 5个低库存商品 |
| **卡密** | 400-1000个 | - 每个自动发货商品10-50个卡密<br>- 80%未售出，20%已售出<br>- 多种卡密格式（账号密码、激活码等） |
| **优惠券** | 20个 | - 15个未使用优惠券<br>- 5个已使用优惠券<br>- 部分优惠券关联到特定商品 |
| **订单** | 100个 | - 20个待支付订单<br>- 50个已完成订单<br>- 15个处理中订单<br>- 10个失败订单<br>- 5个异常订单<br>- 部分订单使用了优惠券 |

**✨ 保留的数据：**
- 邮件模板（5个系统模板）
- 支付方式（34个支付网关）
- 用户账号（管理员账号）

---

## 🚀 使用方法

### 1. 生成测试数据

```bash
# 方式一：使用 Seeder
php artisan db:seed --class=TestDataSeeder

# 方式二：包含在 DatabaseSeeder 中（如果已配置）
php artisan db:seed
```

**执行效果：**
```
🚀 开始生成测试数据...
🗑️  清理旧的测试数据...
✅ 旧数据已清理
📁 生成商品分类...
✅ 创建了 8 个商品分类
🛍️  生成商品...
✅ 创建了 30 个商品
🎫 生成卡密库存...
✅ 创建了 600 个卡密
🎟️  生成优惠券...
✅ 创建了 15 个优惠券
📦 生成订单...
✅ 创建了 100 个订单

🎉 测试数据生成完成！

📊 数据统计：
+----------+--------+
| 类型      | 数量    |
+----------+--------+
| 商品分类   | 8      |
| 商品      | 30     |
| �密       | 600    |
| 优惠券    | 20     |
| 订单      | 100    |
+----------+--------+

💡 提示：使用 php artisan test-data:clear 清空测试数据
```

---

### 2. 清空测试数据

```bash
# 方式一：交互式确认（推荐）
php artisan test-data:clear

# 方式二：强制清空（无需确认）
php artisan test-data:clear --force
```

**执行效果：**
```
⚠️  警告：此操作将清空以下数据：
   - 商品分类
   - 商品
   - 卡密
   - 优惠券
   - 订单

✅ 保留的数据：
   - 邮件模板
   - 支付方式
   - 用户账号

📊 当前数据统计：
+----------+--------+
| 类型      | 数量    |
+----------+--------+
| 商品分类   | 8      |
| 商品      | 30     |
| 卡密      | 600    |
| 优惠券    | 20     |
| 订单      | 100    |
+----------+--------+

 确定要清空这些数据吗？ (yes/no) [no]:
 > yes

🗑️  正在清空数据...
  • 清空优惠券-商品关联...
  • 清空优惠券...
  • 清空订单...
  • 清空卡密...
  • 清空商品...
  • 清空商品分类...

✅ 数据清空完成！
✅ 数据库自增 ID 已重置

💡 提示：使用 php artisan db:seed --class=TestDataSeeder 重新生成测试数据
```

**重要说明：**
- 使用 `truncate` 清空表数据，会自动重置数据库自增 ID
- 清空后再次生成的测试数据，ID 将从 1 开始
- 同时清空了优惠券-商品关联表，避免外键约束问题

---

## 🏭 工厂使用示例

如果需要自定义测试数据，可以直接使用工厂：

### 创建商品分类
```php
use App\Models\GoodsGroup;

// 创建单个分类
$group = GoodsGroup::factory()->create();

// 创建多个分类
$groups = GoodsGroup::factory()->count(5)->create();
```

### 创建商品
```php
use App\Models\Goods;

// 创建自动发货商品
$goods = Goods::factory()->automatic()->create();

// 创建人工处理商品
$goods = Goods::factory()->manual()->create();

// 创建低库存商品
$goods = Goods::factory()->lowStock()->create();

// 创建热门商品
$goods = Goods::factory()->popular()->create();
```

### 创建卡密
```php
use App\Models\Carmis;

// 创建未售出卡密
$carmi = Carmis::factory()->unsold()->create([
    'goods_id' => $goods->id,
]);

// 创建循环使用的卡密
$carmi = Carmis::factory()->loop()->create();
```

### 创建优惠券
```php
use App\Models\Coupon;

// 创建未使用优惠券
$coupon = Coupon::factory()->unused()->create();

// 创建大额优惠券
$coupon = Coupon::factory()->large()->create();

// 创建无限次使用优惠券
$coupon = Coupon::factory()->unlimited()->create();

// 关联商品
$coupon->goods()->attach([1, 2, 3]);
```

### 创建订单
```php
use App\Models\Order;

// 创建待支付订单
$order = Order::factory()->pending()->create();

// 创建已完成订单
$order = Order::factory()->completed()->create();

// 创建失败订单
$order = Order::factory()->failed()->create();
```

---

## 📋 业务场景覆盖

### 商品状态
- ✅ 上架商品（85%）
- ✅ 下架商品（15%）
- ✅ 有库存商品
- ✅ 无库存商品
- ✅ 低库存预警商品

### 订单状态
- ✅ 待支付（20%）
- ✅ 已完成（50%）
- ✅ 处理中（15%）
- ✅ 失败（10%）
- ✅ 异常（5%）

### 优惠券状态
- ✅ 未使用优惠券（75%）
- ✅ 已使用优惠券（25%）
- ✅ 通用优惠券
- ✅ 商品专属优惠券
- ✅ 无限次使用优惠券

### 卡密状态
- ✅ 未售出卡密（80%）
- ✅ 已售出卡密（20%）
- ✅ 循环使用卡密（10%）
- ✅ 多种卡密格式（账号密码、激活码、兑换码等）

---

## ⚠️ 注意事项

1. **开发环境使用** - 仅在开发和测试环境使用，生产环境请勿运行
2. **数据关联** - 测试数据会自动建立关联关系，删除时按关联顺序清理
3. **每次清空** - 每次运行 Seeder 会先清空旧的测试数据
4. **保留系统数据** - 邮件模板和支付方式不会被清空
5. **上线前清理** - 上线前务必运行 `php artisan test-data:clear` 清空测试数据

---

## 🔧 自定义配置

### 修改生成数量

编辑 `database/seeders/TestDataSeeder.php`：

```php
// 修改商品分类数量
$groups = GoodsGroup::factory()
    ->count(10)  // 默认 8 个，改为 10 个
    ->create();

// 修改商品数量
$automaticGoods = Goods::factory()
    ->count(50)  // 默认 20 个，改为 50 个
    ->automatic()
    ->create();

// 修改订单数量
Order::factory()
    ->count(200)  // 默认 100 个，改为 200 个
    ->create();
```

### 修改卡密格式

编辑 `database/factories/CarmisFactory.php`：

```php
$carmiTypes = [
    // 添加自定义格式
    fn () => "自定义格式: ".fake()->uuid(),
];
```

---

## 📞 常见问题

### Q: 为什么运行 Seeder 后数据量不对？
A: Seeder 每次运行会先清空旧数据，确保数据量准确。请检查是否有其他程序在同时操作数据库。

### Q: 可以只清空某一类数据吗？
A: 目前 `test-data:clear` 会清空所有测试数据。如需单独清空，请使用 Tinker：
```bash
php artisan tinker
> Goods::query()->delete();
```

### Q: 如何在上线前确保测试数据已清空？
A: 运行以下命令检查：
```bash
php artisan test-data:clear
```
如果数据统计都为 0，说明已清空。

### Q: 测试数据会影响生产数据吗？
A: 不会。测试数据 Seeder 保留了邮件模板和支付方式等系统配置，只清空业务数据。

---

## 📝 更新日志

### v1.0.0 (2025-11-02)
- ✅ 创建 GoodsGroup、Goods、Carmis、Coupon、Order 工厂
- ✅ 创建 TestDataSeeder 测试数据播种器
- ✅ 创建 test-data:clear 命令用于清空测试数据
- ✅ 实现数据关联和业务场景覆盖
