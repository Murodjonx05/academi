# ✅ ЧЕКЛИСТ ВНЕДРЕНИЯ - 15 минут

## 🚀 Быстрый старт (Минимальное внедрение)

### Шаг 1: Проверка файлов (1 мин)
```bash
cd /home/aestra/Рабочий\ стол/moodle/public/theme/academi

# Проверьте, что созданы:
ls scss/_design-system.scss
ls scss/_typography-unified.scss
ls scss/_navbar-modern.scss
ls templates/navbar-modern.mustache
```

### Шаг 2: Обновить theme.scss (2 мин)
✅ УЖЕ СДЕЛАНО! Файл обновлен.

Проверьте первые строки `scss/theme.scss`:
```scss
@import 'design-system';
@import 'typography-unified';
@import 'navbar-modern';
```

### Шаг 3: Очистить кэш (1 мин)
```bash
# Вариант 1: CLI
php admin/cli/purge_caches.php

# Вариант 2: Браузер
# Перейдите: Site administration > Development > Purge all caches
```

### Шаг 4: Добавить метод в renderer (5 мин)

Откройте: `classes/output/core_renderer.php`

Добавьте в конец класса:
```php
/**
 * Get modern navbar data
 */
public function navbar_modern_data() {
    global $USER, $CFG;
    
    return [
        'logo' => theme_academi_get_logo_url('header'),
        'sitename' => $this->page->heading,
        'userloggedin' => isloggedin() && !isguestuser(),
        'userfullname' => fullname($USER),
        'useravatar' => $this->user_picture($USER, ['size' => 35, 'link' => false]),
        'navitems' => [
            ['text' => 'Home', 'url' => $CFG->wwwroot, 'isactive' => true],
            ['text' => 'Courses', 'url' => $CFG->wwwroot . '/course/'],
        ],
        'usermenu' => [
            ['text' => 'Profile', 'url' => $CFG->wwwroot . '/user/profile.php'],
            ['text' => 'Logout', 'url' => $CFG->wwwroot . '/login/logout.php?sesskey=' . sesskey()],
        ],
    ];
}

public function render_modern_navbar() {
    return $this->render_from_template('theme_academi/navbar-modern', $this->navbar_modern_data());
}
```

### Шаг 5: Обновить layout (3 мин)

Откройте: `layout/frontpage.php`

Найдите строку с header (примерно строка 30-40):
```php
// СТАРОЕ:
echo $OUTPUT->navbar();

// ЗАМЕНИТЕ НА:
echo $OUTPUT->render_modern_navbar();
```

Повторите для других layout файлов:
- `layout/drawers.php`
- `layout/columns1.php`
- `layout/columns2.php`

### Шаг 6: Очистить кэш снова (1 мин)
```bash
php admin/cli/purge_caches.php
```

### Шаг 7: Тест (2 мин)

Откройте сайт в браузере:
1. ✅ Navbar отображается
2. ✅ Работает на desktop
3. ✅ Работает на mobile (F12 > Device toolbar)
4. ✅ Hamburger menu открывается
5. ✅ Dropdown меню работают

---

## 🔧 Расширенное внедрение (Опционально)

### Добавить больше nav items

В методе `navbar_modern_data()`:
```php
'navitems' => [
    [
        'text' => get_string('home'),
        'url' => $CFG->wwwroot,
        'isactive' => $PAGE->pagetype === 'site-index',
        'isdropdown' => false,
    ],
    [
        'text' => get_string('courses'),
        'url' => '#',
        'isdropdown' => true,
        'children' => [
            ['text' => 'All Courses', 'url' => $CFG->wwwroot . '/course/'],
            ['text' => 'My Courses', 'url' => $CFG->wwwroot . '/my/courses.php'],
        ],
    ],
    // Добавьте свои...
],
```

### Настроить цвета

В админ-панели:
```
Site administration > Appearance > Themes > Academi

Primary Color: #2563eb
Secondary Color: #16a34a
Nav Background: #ffffff
Nav Text: #475569
```

---

## 🐛 Troubleshooting

### Navbar не отображается
```bash
# 1. Проверьте файлы
ls templates/navbar-modern.mustache
ls scss/_navbar-modern.scss

# 2. Очистите кэш
php admin/cli/purge_caches.php

# 3. Проверьте консоль браузера (F12)
```

### Стили не применяются
```bash
# 1. Проверьте theme.scss
head -20 scss/theme.scss

# 2. Пересоберите SCSS
# Site administration > Development > Purge all caches

# 3. Очистите кэш браузера
# Ctrl+Shift+R (Windows/Linux)
# Cmd+Shift+R (Mac)
```

### JavaScript не работает
```php
// В layout файле добавьте:
$PAGE->requires->js_call_amd('theme_academi/theme', 'init');
```

---

## 📱 Тестирование на разных устройствах

### Desktop
```
✓ Chrome (F12 > Responsive)
✓ Firefox (Ctrl+Shift+M)
✓ Safari (Develop > Enter Responsive Design Mode)
```

### Mobile
```
✓ 320px (iPhone SE)
✓ 375px (iPhone X)
✓ 414px (iPhone Plus)
✓ 768px (iPad)
```

### Проверьте:
```
✓ Navbar виден
✓ Hamburger menu работает
✓ Dropdown открываются
✓ Текст не обрезается
✓ Элементы не наезжают
✓ Плавные анимации
```

---

## ✅ Финальная проверка

- [ ] Файлы созданы
- [ ] theme.scss обновлен
- [ ] Renderer метод добавлен
- [ ] Layout файлы обновлены
- [ ] Кэш очищен (2 раза!)
- [ ] Navbar отображается
- [ ] Работает на 320px+
- [ ] Hamburger menu работает
- [ ] Dropdown меню работают
- [ ] Нет JavaScript ошибок
- [ ] Нет CSS ошибок

**Если все ✅ - ПОЗДРАВЛЯЮ! Navbar модернизирован! 🎉**

---

## 📞 Нужна помощь?

1. Проверьте `MODERNIZATION_GUIDE.md`
2. Посмотрите `BEFORE_AFTER_COMPARISON.md`
3. Изучите код в файлах (есть комментарии)
4. Проверьте консоль браузера (F12)

**Время внедрения: ~15 минут**
**Результат: Современный, адаптивный navbar! 🚀**
