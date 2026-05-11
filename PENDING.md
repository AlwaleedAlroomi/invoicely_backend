# Pending Technical Decisions — Invoicely Backend

## Open Items (To Be Resolved Before/During Execution)

---

### 1. PostgreSQL Migration Strategy
**Status**: ⏳ Pending
**Question**: هل يتم ترحيل SQLite الموجود (بيانات وهمية) إلى PostgreSQL، أم نبدأ بقاعدة بيانات PostgreSQL جديدة فارغة؟
**Clarification**: المشروع الحالي يستخدم SQLite للتطوير. بعد التحويل إلى PostgreSQL، يجب حذف SQLite الحالي وبدء PostgreSQL من الصفر.

**Recommendation**: ابدأ PostgreSQL من صفر. لا حاجة لترحيل البيانات الموجودة (SQLite).

---

### 2. API Versioning Strategy
**Status**: ⏳ Pending
**Question**: هل نستخدم versioning صريح في مسار API (`/api/v1/invoices`) أم نبدأ بدون versioning ونضيفه لاحقاً؟
**Options**:
- `/api/v1/*` — استباقي، يسمح بترقية الـ API دون كسر التوافق مع Flutter
- `/api/*` — أبسط، يضاف versioning لاحقاً عند الحاجة

**Recommendation**: استخدم `/api/v1/*` من اليوم الأول. التكلفة صفر، والمرونة عالية.

---

### 3. Flutter-Backend Contract (API Response Format)
**Status**: ⏳ Pending — يتطلب تنسيق مع Flutter team
**Question**: هل يوجد تنسيق استجابة (Response Envelope) محدد مطلوب من تطبيق Flutter؟ مثلاً:
```json
{
  "success": true,
  "data": { ... },
  "message": "...",
  "meta": { "current_page": 1, "per_page": 15, "total": 100 }
}
```
أم نستخدم تنسيق Laravel JsonResource الافتراضي؟

**Recommendation**: استخدم تنسيق Laravel JsonResource الافتراضي. إن كان Flutter يحتاج تنسيقاً مختلفاً، يضاف لاحقاً.

---

### 4. Team Resolution Strategy for API
**Status**: ⏳ Pending
**Question**: كيف يحدد الـ API الـ team النشط عند كل request من Flutter؟
**Options**:
- **Header-based**: `X-Team-Id` header في كل request → الأكثر شيوعاً للـ APIs
- **Route-based**: `/api/teams/{team}/invoices` → يشبه الـ web routes
- **User-default**: user له `current_team_id` ويستخدم ضمنياً

**Recommendation**: `X-Team-Id` header. أنظف للـ API ويتوافق مع Stateless design.

---

### 5. Localization Strategy
**Status**: ⏳ Pending — يتطلب تنسيق مع Flutter team
**Question**: Flutter سيرسل `Accept-Language: ar` أو `ar-SA` في الـ headers. الـ Backend سيرد بالرسائل باللغة المطلوبة. هل نحتاج لدعم لغوي في الـ API responses (مثل أسماء الحقول المترجمة) أم فقط رسائل الخطأ والـ validation تكون بالعربية؟

**Recommendation**: استخدم `Accept-Language` header. فعّل `Laravel localization` لرسائل الخطأ فقط. محتوى البيانات (أسماء العملاء، المنتجات) لا يُترجم.

---

### 6. Soft Deletes Behavior
**Status**: ⏳ Pending
**Question**: هل الـ "حذف" النهائي (forceDelete) مسموح به لأي مستخدم؟
**Policy**: مالك فقط يمكنه forceDelete. Admin/Member يمكنهم soft delete فقط.
**Implementation**: الأجندة الحالية تدعم soft deletes فقط في الـ API. forceDelete يتم من Filament للمالك فقط.

**Recommendation**: الـ API يدعم soft deletes فقط. forceDelete من Filament للمالك فقط.

---

### 7. Number Sequence Reset Strategy
**Status**: ⏳ Pending — يتطلب تأكيد من Owner
**Question**: هل رقم الفاتورة يبدأ من 1 كل سنة (INV-2026-00001) أم يبقى sequential مستمر (INV-00001)؟

**Recommendation**: `Yearly reset` مع prefix للسنة (INV-2026-XXXXX). يُضبط من TeamSettings في Filament.

---

### 8. Filament Multi-Tenancy Scope
**Status**: ⏳ Pending
**Question**: في Filament dashboard، هل المالك يرى كل الفرق (Teams) مرة واحدة، أم يختار فريقاً معيناً ليرى بياناته؟

**Recommendation**: المالك يرى كل البيانات لكل الفرق في صفحة واحدة (إشراف كامل). لا نحتاج لـ Filament multi-tenancy plugin لأن المالك هو owner لكل الفرق.
