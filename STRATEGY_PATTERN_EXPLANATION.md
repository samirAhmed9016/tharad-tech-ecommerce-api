# Strategy Pattern Explained - Payment Processing Context

## 🎯 What is the Strategy Pattern?

The **Strategy Pattern** is a design pattern that allows you to define a family of algorithms (strategies), encapsulate each one, and make them **interchangeable**. The pattern lets the algorithm vary independently from the clients that use it.

### Real-World Analogy (Payment Context)
Think of payment methods like different ways to pay:
- **Credit Card** - one strategy
- **PayPal** - another strategy  
- **Bank Transfer** - another strategy
- **Cash on Delivery** - another strategy

All of them achieve the same goal (process payment), but each has different steps and rules. The Strategy Pattern lets you:
- Add new payment methods easily
- Change payment logic without breaking existing code
- Test each payment method independently
- Switch between payment methods at runtime

---

## 📐 The Pattern Structure

### 1. **Strategy Interface** (Contract)
Defines what all payment strategies must do:
```php
interface PaymentStrategy {
    public function process(float $amount): PaymentResult;
    public function validate(): bool;
    public function refund(string $transactionId): bool;
}
```

### 2. **Concrete Strategies** (Different Payment Methods)
Each payment method implements the interface:
```php
class CreditCardStrategy implements PaymentStrategy { ... }
class PayPalStrategy implements PaymentStrategy { ... }
class BankTransferStrategy implements PaymentStrategy { ... }
```

### 3. **Context** (The Payment Processor)
Uses the strategy without knowing which specific one:
```php
class PaymentProcessor {
    private PaymentStrategy $strategy;
    
    public function setStrategy(PaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }
    
    public function processPayment(float $amount) {
        return $this->strategy->process($amount);
    }
}
```

---

## 🎨 Why Use Strategy Pattern for Payments?

### ❌ **Without Strategy Pattern (Bad Way)**
```php
public function checkout($paymentMethod) {
    if ($paymentMethod === 'credit_card') {
        // 50 lines of credit card logic
    } elseif ($paymentMethod === 'paypal') {
        // 50 lines of PayPal logic
    } elseif ($paymentMethod === 'bank_transfer') {
        // 50 lines of bank transfer logic
    }
    // Adding new payment method = modifying this function = risk!
}
```

**Problems:**
- ❌ Hard to maintain (one giant function)
- ❌ Hard to test (must test all methods together)
- ❌ Violates Open/Closed Principle (must modify existing code)
- ❌ Tight coupling (all payment logic in one place)

### ✅ **With Strategy Pattern (Good Way)**
```php
public function checkout($paymentMethod) {
    $strategy = PaymentStrategyFactory::make($paymentMethod);
    return $strategy->process($amount);
}
```

**Benefits:**
- ✅ Easy to add new payment methods (just create new class)
- ✅ Easy to test (test each strategy independently)
- ✅ Follows Open/Closed Principle (open for extension, closed for modification)
- ✅ Loose coupling (each strategy is independent)
- ✅ Single Responsibility (each class does one thing)

---

## 🏗️ How It Works in Laravel

### Step 1: Create Strategy Interface
```php
namespace App\Services\Payments\Contracts;

interface PaymentStrategyInterface {
    public function process(float $amount, array $data): PaymentResult;
    public function validate(array $data): bool;
}
```

### Step 2: Create Concrete Strategies
```php
namespace App\Services\Payments\Strategies;

class CreditCardStrategy implements PaymentStrategyInterface {
    public function process(float $amount, array $data): PaymentResult {
        // Credit card specific logic
    }
}

class PayPalStrategy implements PaymentStrategyInterface {
    public function process(float $amount, array $data): PaymentResult {
        // PayPal specific logic
    }
}
```

### Step 3: Create Context/Service
```php
namespace App\Services\Payments;

class PaymentService {
    public function __construct(
        private PaymentStrategyInterface $strategy
    ) {}
    
    public function processPayment(float $amount, array $data) {
        return $this->strategy->process($amount, $data);
    }
}
```

### Step 4: Use Service Container (Laravel's Dependency Injection)
```php
// In ServiceProvider
$this->app->when(PaymentService::class)
    ->needs(PaymentStrategyInterface::class)
    ->give(function ($app) {
        $method = request()->input('payment_method');
        return match($method) {
            'credit_card' => new CreditCardStrategy(),
            'paypal' => new PayPalStrategy(),
            default => throw new \Exception('Invalid payment method')
        };
    });
```

---

## 🔄 The Flow in Your E-commerce Project

### Current Flow (Without Strategy):
```
User clicks checkout → OrderRepository::checkout() → Creates order (no payment)
```

### Future Flow (With Strategy):
```
User clicks checkout with payment method
    ↓
OrderRepository::checkout() 
    ↓
PaymentService (uses Strategy Pattern)
    ↓
Specific Payment Strategy (CreditCard/PayPal/etc.)
    ↓
Process payment → Update order status → Return result
```

---

## 📝 Key Principles

1. **Encapsulation**: Each payment method is in its own class
2. **Polymorphism**: All strategies implement the same interface
3. **Composition**: PaymentService uses a strategy (has-a relationship)
4. **Dependency Inversion**: Depend on abstractions (interface), not concretions

---

## 🎓 Summary

**Strategy Pattern = "Different ways to do the same thing"**

- **Interface** = Contract (what must be done)
- **Strategies** = Different implementations (how it's done)
- **Context** = Uses the strategy (orchestrates the process)

**For Payments:**
- Interface = PaymentStrategyInterface (must process payment)
- Strategies = CreditCardStrategy, PayPalStrategy, etc. (how each processes)
- Context = PaymentService (orchestrates payment processing)

---

## 🚀 Next Steps

1. We'll create the payment strategy interface
2. We'll create concrete payment strategies (start with 2-3 examples)
3. We'll create a PaymentService to use strategies
4. We'll integrate it into your OrderRepository
5. We'll add payment method selection to your checkout flow

