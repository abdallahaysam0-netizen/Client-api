# دليل نظام الصلاحيات (Roles & Permissions Guide)

تم إعداد هذا النظام لربط حماية الـ Backend (Laravel) مع مرونة الـ Frontend (React).

## 1. هيكلية الأدوار (Roles Structure)

| الدور (Role) | الوصف | الصلاحيات |
| :--- | :--- | :--- |
| **Admin** | مدير النظام | يمتلك كافة صلاحيات العرض، الإضافة، التعديل، والحذف لجميع الأقسام. |
| **Manager** | موظف إداري | يمتلك صلاحيات العرض فقط (View) لجميع الأقسام بدون قدرة على التعديل أو الحذف. |

---

## 2. كيفية عمل الحماية (Backend)

### حماية المسارات (Middleware)
تم استخدام الـ Middleware الخاص بـ `Spatie` في ملف `api.php`:
```php
Route::middleware(['auth:api', 'permission:view-clients'])->get('/clients', ...);
```

### حماية الخدمات (Service Layer)
تمت إضافة حماية برمجية داخل فئة الخدمات لضمان الأمان حتى لو تم تجاوز الروابط:
```php
Gate::authorize('view-clients'); // داخل ClientService.php
```

### استجابة تسجيل الدخول (Login Response)
يرسل السيرفر كائن المستخدم مع مصفوفات الصلاحيات:
```json
"user": {
    "roles": ["Admin"],
    "permissions": ["view-clients", "create-clients", ...]
}
```

---

## 3. كيفية عمل الواجهة (Frontend)

### التحقق من الصلاحية
تمت إضافة دالة `hasPermission` في المكونات (مثل `Navbar` و `Clients`) للتحقق من الصلاحيات:
```javascript
const hasPermission = (perm) => user.permissions.includes(perm) || user.roles.includes('Admin');
```

### التحكم في العناصر
يتم إخفاء أو إظهار الأزرار والروابط بناءً على الشرط:
```javascript
{hasPermission('create-clients') && <button>إضافة عميل</button>}
```

---

## 4. نصائح للمطور (Troubleshooting)
1. **عند تغيير صلاحية مستخدم:** يجب على المستخدم عمل **تسجيل خروج (Logout)** ثم **دخول** مرة أخرى لتحديث البيانات في الـ `localStorage`.
2. **عند إضافة قسم جديد:** يجب إضافة الصلاحيات الخاصة به في ملف `RolesAndPermissionsSeeder.php` وتشغيله.
3. **أمان الـ API:** الواجهة تخفي الأزرار للجمالية (UI/UX)، ولكن الـ Backend هو المسؤول الحقيقي عن منع العملية إذا حاول شخص اختراقها.

---
**تم الإعداد بواسطة: Antigravity AI Assistant**
**التاريخ: 2026-05-14**
