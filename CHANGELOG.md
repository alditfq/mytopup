# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-01-XX

### Added
- ✨ Game accounts marketplace with full CRUD
- 📧 Email delivery system for topup success and account credentials
- 🎁 Promo code system with validation
- ⭐ Review and rating system for transactions
- 👑 Comprehensive admin panel with analytics
- 📊 Revenue chart and transaction statistics
- 🛡️ Admin suspension system
- 🔐 Form Request validation layer
- 📧 Email templates (TopupSuccessMail, AccountDeliveryMail)
- 🎨 Custom error pages (403, 404, 500)
- 🔒 Anti double-checkout protection
- 📱 Responsive design for mobile and desktop
- 🎮 Game catalog with categories and search
- 💳 Multiple payment methods (QRIS, E-Wallet, Bank Transfer)
- 📦 Service layer architecture (PromoService, TransactionService, FulfillmentService)

### Changed
- 🔄 Refactored controllers to use Form Requests
- 🎨 Updated UI with neumorphic design system
- 📝 Improved README with detailed documentation
- 🗑️ Removed unused features (Chat, FAQ, Testimonials, Marquee)
- 🌐 Removed language switcher from navbar

### Fixed
- 🐛 Price integrity validation
- 🔒 CSRF protection on all forms
- 🚫 Admin middleware suspension check

### Security
- 🔐 Password hashing with bcrypt
- 🛡️ SQL injection prevention with Eloquent ORM
- 🔒 XSS protection with Blade templating
- 🚫 CSRF token validation
- 🔑 Encrypted account credentials storage

## [0.1.0] - Initial Development

### Added
- Basic Laravel 12 setup
- Database schema design
- Initial models and migrations
- Basic authentication system
