# AGENTS.md - ILDIS Development Guidelines

This document provides guidelines for agentic coding agents working on the ILDIS (Indonesian Law Documentation Information System) codebase.

## Project Overview

- **Framework**: Yii 2 (PHP)
- **Testing**: Codeception
- **Database**: MySQL/MariaDB
- **Architecture**: Advanced template with backend/frontend/common separation

---

## Build, Lint, and Test Commands

### Running Tests

Run all tests across all applications:
```bash
vendor/bin/codecept run
```

Run tests for a specific application:
```bash
vendor/bin/codecept run -c backend      # Backend tests
vendor/bin/codecept run -c frontend     # Frontend tests
vendor/bin/codecept run -c common       # Common tests
```

Run a **single test**:
```bash
vendor/bin/codecept run -c common unit models/LoginFormTest    # Single test file
vendor/bin/codecept run -c frontend functional LoginCest      # Single Cest file
vendor/bin/codecept run --filter testMethodName               # Filter by method name
```

Run specific test suite:
```bash
vendor/bin/codecept run unit       # Unit tests
vendor/bin/codecept run functional # Functional tests
vendor/bin/codecept run acceptance # Acceptance tests (if configured)
```

### Development Server

Initialize the Advanced Template first if the root `yii` script is absent. Run
`php init` and select the Development environment; then start the PHP built-in
server:
```bash
php yii serve                         # Default port 8080
php yii serve --port=9000            # Custom port
```

### Composer

Install dependencies:
```bash
composer install
```

Use `composer update` only when intentionally changing dependency versions and
review the resulting `composer.lock` diff. Do not ignore platform requirements
as a routine workaround; match the PHP version and extensions required by the
project instead.

### Database

Run migrations (if using Yii migrations):
```bash
php yii migrate
php yii migrate/down                 # Rollback last migration
```

---

## Code Style Guidelines

### General Conventions

- **PHP Version**: PHP 8.3+ (matches `composer.json` and the production Docker image)
- **Indentation**: 4 spaces (no tabs)
- **Line Endings**: Unix-style (LF)
- **Max Line Length**: 120 characters (soft limit)
- **Opening Brace**: Same line for classes/functions, new line for control structures

### File Organization

```
backend/           # Admin dashboard application
frontend/          # Public-facing website
common/            # Shared code (models, components, config)
console/           # CLI commands
```

### Namespace Conventions

- Backend controllers: `backend\controllers`
- Frontend controllers: `frontend\controllers`
- Common models: `common\models`
- Use singular names for classes (e.g., `User`, not `Users`)

### Naming Conventions

| Element | Convention | Example |
|---------|-----------|---------|
| Classes | PascalCase | `LoginForm`, `UserController` |
| Methods | camelCase | `actionLogin()`, `validatePassword()` |
| Variables | camelCase | `$username`, `$failedLogins` |
| Constants | UPPER_SNAKE_CASE | `MAX_LOGIN_ATTEMPTS` |
| Database Tables | snake_case | `user_member`, `tipe_dokumen` |
| Views | snake_case | `login.php`, `user-profile.php` |

### Class Structure (Yii2 Models)

```php
<?php
namespace common\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;  // Private properties prefixed with underscore

    public function rules()
    {
        return [
            // Define validation rules here
        ];
    }

    public function validatePassword($attribute, $params)
    {
        // Inline validation logic
    }
}
```

### Class Structure (Yii2 Controllers)

```php
<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [...],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [...],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
```

### Import Statements

- Use explicit imports (avoid `use Yii;` for everything)
- Group imports: PHP core, Yii framework, third-party, application
- Order alphabetically within groups

```php
use Yii;
use yii\base\Model;
use yii\web\Controller;
use common\models\User;
use kartik\grid\GridView;
```

### Yii2-Specific Patterns

1. **ActiveRecord Models**: Use Yii2 AR conventions, define `tableName()` if custom table
2. **Form Models**: Extend `yii\base\Model`, define `rules()` and attribute labels
3. **Controllers**: Use `action*` prefix for actions, implement proper access control
4. **Views**: Use Yii2 widgets (GridView, ActiveForm) where possible
5. **Database Queries**: Use Query Builder or ActiveQuery for complex queries

### Error Handling

- Use Yii2's built-in exception handling
- Throw `yii\web\NotFoundHttpException` for 404s
- Use `Yii::$app->session->setFlash()` for user notifications
- Log errors with `Yii::error()` or `Yii::warning()`

```php
if (!$model) {
    throw new \yii\web\NotFoundHttpException('Data tidak ditemukan.');
}

try {
    // Operation that might fail
} catch (\Exception $e) {
    Yii::error('Error: ' . $e->getMessage());
    Yii::$app->session->setFlash('error', 'Terjadi kesalahan sistem.');
}
```

### HTML and Views

- Use Yii2 HTML helpers: `Html::encode()`, `Html::a()`, `Html::submitButton()`
- Escape output with `Html::encode()` to prevent XSS
- Use ActiveForm for form inputs

```php
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
```

### SQL and Database

- Use parameterized queries to prevent SQL injection
- Use Yii2 Query Builder or ActiveRecord for database operations
- Add comments for complex queries

```php
$users = User::find()
    ->where(['status' => User::STATUS_ACTIVE])
    ->orderBy('created_at DESC')
    ->all();
```

### Comments

- Use PHPDoc for classes, properties, and methods
- Keep comments in English (or Indonesian if specifically required)
- Comment complex business logic, not obvious code

```php
/**
 * Validates the password.
 * This method serves as the inline validation for password.
 *
 * @param string $attribute the attribute currently being validated
 * @param array $params additional name-value pairs given in the rule
 */
public function validatePassword($attribute, $params)
{
    // Implementation
}
```

### Testing Conventions (Codeception)

- Test files: `*Test.php` for unit tests, `*Cest.php` for functional tests
- Test class names: Same as file name
- In unit tests, use PHPUnit assertions (`$this->assert*`) or Codeception's `expect()`.
  In Cest tests, use the actor assertions (`$I->assert*`).
- Use fixtures for test data

```php
<?php
namespace common\tests;

use common\models\LoginForm;
use Codeception\Test\Unit;

class LoginFormTest extends Unit
{
    protected $tester;

    public function testValidatePassword()
    {
        $model = new LoginForm();
        $model->username = 'test';
        $model->password = 'wrong';
        $this->assertFalse($model->validate());
    }
}
```

### Security Considerations

- Never commit secrets, API keys, or credentials
- Use `.env` for configuration, not hardcoded values
- Validate and sanitize all user inputs
- Use CSRF protection on forms (`ActiveForm::begin()` includes it by default)
- Escape output to prevent XSS attacks

### JavaScript and Frontend

- Keep JavaScript minimal; use Yii2 widgets where possible
- Use `Html::js` or register scripts properly with `Yii::$app->view->registerJs()`
- Follow existing JavaScript patterns in the codebase

---

## Common Development Tasks

### Creating a New Controller
1. Create file in `backend/controllers/` or `frontend/controllers/`
2. Extend `yii\web\Controller`
3. Define actions with `action*` prefix
4. Add access control in `behaviors()`

### Creating a New Model
1. Create file in `common/models/` (for shared) or app-specific models
2. Extend `yii\base\Model` for forms or `yii\db\ActiveRecord` for database
3. Define `rules()`, `attributeLabels()`, and methods

### Creating a Migration
```bash
php yii migrate/create create_user_table
```

### Running Gii (Code Generator)
Access via `/gii` route in development environment.