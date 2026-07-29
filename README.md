# نظام إدارة صالون تجميل (مواعيد، خدمات، دفع)
==========================

### Overview & Project Purpose

نظام إدارة صالون تجميل هو تطبيق إلكتروني مخصص لتعزيز تجربة العملاء في صالونات التجميل. يتيح للمستخدمين إدارة مواعيد، خدمات، وعمليات الدفع بسهولة ويسر. هذا المشروع مصمم لتحسين كفاءة العمل في الصالونات وتحسين تجربة العملاء.

### Project Structure Mapping

- `docker/`: مجلد يحتوي على الملفات اللازمة لتشغيل المشروع باستخدام docker-compose.
- `src/`: مجلد يحتوي على الكود المصدر للبرنامج.
- `src/app/`: مجلد يحتوي على الكود المصدر للبرنامج.
- `src/db/`: مجلد يحتوي على الكود المصدر لฐาน البيانات.
- `src/models/`: مجلد يحتوي على الكود المصدر للنموذج.
- `src/routes/`: مجلد يحتوي على الكود المصدر للطرق.
- `src/services/`: مجلد يحتوي على الكود المصدر للخدمات.
- `src/utils/`: مجلد يحتوي على الكود المصدر للوظائف الутиلية.

### Step-by-Step Instructions for Running the Environment

1. **تثبيت dependencies**: استخدم الأمر `npm install` لتنزيل dependencies المطلوبة.
2. **تشغيل docker-compose**: استخدم الأمر `docker-compose up` لتشغيل المشروع باستخدام docker-compose.
3. **تشغيل container**: استخدم الأمر `docker-compose exec app bash` لتشغيل shell داخل container.
4. **تشغيل البرنامج**: استخدم الأمر `node src/app.js` لتشغيل البرنامج.

### Modules, Tables, and Roles

- **Modules**:
 - `appointments`: إدارة مواعيد العملاء.
 - `services`: إدارة خدمات الصالون.
 - `payments`: إدارة عمليات الدفع.
- **Tables**:
 - `customers`: معلومات العملاء.
 - `appointments`: معلومات مواعيد العملاء.
 - `services`: معلومات خدمات الصالون.
 - `payments`: معلومات عمليات الدفع.
- **Roles**:
 - `admin`: دور الإدارة.
 - `staff`: دور الموظفين.
 - `customer`: دور العملاء.

### Contact Developer Details

- **Developer Name**: [Your Name]
- **Developer Email**: [your email]
- **Developer Phone**: [your phone number]
- **Developer GitHub**: [your GitHub profile]

Note: Please replace `[Your Name]`, `[your email]`, `[your phone number]`, and `[your GitHub profile]` with your actual details.

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
