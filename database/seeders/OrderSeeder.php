<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    private $egyptianCities = [
        'Cairo',
        'Alexandria',
        'Giza',
        'Shubra El-Kheima',
        'Port Said',
        'Suez',
        'Luxor',
        'Mansoura',
        'El-Mahalla El-Kubra',
        'Tanta',
        'Asyut',
        'Ismailia',
        'Faiyum',
        'Zagazig',
        'Aswan',
        'Damietta',
        'Damanhur',
        'Minya',
        'Beni Suef',
        'Qena',
        'Sohag',
        'Hurghada',
        'Shibin El Kom',
        'Banha',
        'Kafr el-Sheikh',
        'Arish',
        'Mallawi',
        '10th of Ramadan City',
        '6th of October City',
        'Obour City'
    ];

    private $egyptianNames = [
        'Mohamed Ahmed',
        'Ahmed Hassan',
        'Mahmoud Ali',
        'Ali Mohamed',
        'Hassan Ibrahim',
        'Ibrahim Mahmoud',
        'Youssef Ahmed',
        'Omar Hassan',
        'Khaled Mohamed',
        'Amr Ali',
        'Mustafa Ibrahim',
        'Tamer Ahmed',
        'Sherif Hassan',
        'Karim Mohamed',
        'Hossam Ali',
        'Fatma Ahmed',
        'Nour Hassan',
        'Mona Ibrahim',
        'Sara Mohamed',
        'Dina Ali',
        'Heba Mahmoud',
        'Mariam Ahmed',
        'Yasmin Hassan',
        'Aya Mohamed',
        'Nada Ibrahim',
        'Rania Ali',
        'Salma Ahmed',
        'Mai Hassan',
        'Rana Mohamed',
        'Nesma Ibrahim',
        'Abdullah Ahmed',
        'Ziad Hassan',
        'Adel Mohamed',
        'Sami Ali',
        'Waleed Ibrahim',
        'Tarek Ahmed',
        'Essam Hassan',
        'Magdy Mohamed',
        'Samir Ali',
        'Fady Ibrahim'
    ];

    private $streetNames = [
        'El Tahrir',
        'El Nasr',
        'El Gomhoria',
        'El Horreya',
        'El Geish',
        'Ramses',
        'Salah Salem',
        'El Mohandeseen',
        'El Thawra',
        'El Hegaz',
        'Makram Ebeid',
        'Ahmed Orabi',
        'Mostafa El Nahas',
        'El Orouba',
        'El Merghany'
    ];

    private $orderNotes = [
        'Please call before delivery',
        'Leave at door if not home',
        'Delivery between 2-5 PM',
        'Ring the bell twice',
        'Call when arrived',
        null,
        null,
        null, // More orders without notes
    ];

    public function run(): void
    {
        $users = User::all();
        $products = Product::where('status', true)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->error('Please seed users and products first!');
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentMethods = ['cash', 'card', 'online'];
        $paymentStatuses = ['pending', 'paid', 'failed'];

        $this->command->info('Creating 500 orders...');
        $bar = $this->command->getOutput()->createProgressBar(500);

        for ($i = 1; $i <= 500; $i++) {
            $user = $users->random();
            $status = $this->weightedStatus($statuses);
            $paymentMethod = $this->weightedPaymentMethod($paymentMethods);
            $paymentStatus = $this->getPaymentStatus($status, $paymentMethod);

            // Random order date within last 6 months
            $createdAt = now()->subDays(rand(0, 180))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $order = Order::create([
                'order_number' => $this->generateOrderNumber($i),
                'user_id' => $user->id,
                'shipping_name' => $this->egyptianNames[array_rand($this->egyptianNames)],
                'shipping_phone' => $this->generateEgyptianPhone(),
                'shipping_address' => $this->generateAddress(),
                'shipping_city' => $this->egyptianCities[array_rand($this->egyptianCities)],
                'shipping_postal_code' => rand(11111, 99999),
                'total_price' => 0, // Will calculate after items
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'notes' => $this->orderNotes[array_rand($this->orderNotes)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Add 1-5 items per order
            $itemCount = rand(1, 5);
            $totalPrice = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $quantity;
                $totalPrice += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Update order total price
            $order->update(['total_price' => $totalPrice]);

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('500 orders created successfully!');
    }

    private function generateOrderNumber($index): string
    {
        return 'ORD-' . date('Y') . '-' . str_pad($index, 6, '0', STR_PAD_LEFT);
    }

    private function generateEgyptianPhone(): string
    {
        $prefixes = ['010', '011', '012', '015'];
        return $prefixes[array_rand($prefixes)] . rand(10000000, 99999999);
    }

    private function generateAddress(): string
    {
        $street = $this->streetNames[array_rand($this->streetNames)];
        $building = rand(1, 200);
        $floor = rand(1, 10);
        $apartment = rand(1, 20);

        return "{$building} {$street} St., Floor {$floor}, Apt {$apartment}";
    }

    private function weightedStatus($statuses): string
    {
        // 50% completed, 25% processing, 15% pending, 10% cancelled
        $rand = rand(1, 100);
        if ($rand <= 50) return 'completed';
        if ($rand <= 75) return 'processing';
        if ($rand <= 90) return 'pending';
        return 'cancelled';
    }

    private function weightedPaymentMethod($methods): string
    {
        // 60% cash, 30% card, 10% online
        $rand = rand(1, 100);
        if ($rand <= 60) return 'cash';
        if ($rand <= 90) return 'card';
        return 'online';
    }

    private function getPaymentStatus($orderStatus, $paymentMethod): string
    {
        if ($orderStatus === 'completed') {
            return 'paid';
        }
        if ($orderStatus === 'cancelled') {
            return rand(0, 1) ? 'failed' : 'pending';
        }
        if ($paymentMethod === 'cash') {
            return 'pending';
        }
        // For card/online in processing/pending orders
        return rand(0, 1) ? 'paid' : 'pending';
    }
}
