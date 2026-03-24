# 🎨 Jake Barton Website - Style Kit

Drop these files into any webpage to get the same look, feel, and effects as the new portfolio.

## 📁 File Structure

```
style-kit/
├── README.md              ← You are here
├── styles/
│   ├── base.css           ← Reset, body, fonts, CSS variables (colors, spacing)
│   ├── components.css     ← Buttons, cards, nav, glass surfaces
│   └── animations.css     ← All keyframe animations + utility classes
├── js/
│   ├── cursor-ribbons.js  ← Cyan ribbon cursor trail effect
│   ├── fuzzy-text.js      ← Glitchy/fuzzy canvas text effect
│   ├── beams-bg.js        ← Animated beam background (WebGL via OGL)
│   └── effects.js         ← Smooth scroll, staggered reveals, hover effects
└── example.html           ← Full working demo page using all effects
```

## 🚀 Quick Start

Add this to any `.html` / `.php` page:

```html
<head>
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
  
  <!-- Styles (order matters) -->
  <link rel="stylesheet" href="style-kit/styles/base.css">
  <link rel="stylesheet" href="style-kit/styles/animations.css">
  <link rel="stylesheet" href="style-kit/styles/components.css">
</head>

<body>
  <!-- Your content here -->

  <!-- Effects (load at end of body) -->
  <script src="style-kit/js/cursor-ribbons.js"></script>
  <script src="style-kit/js/fuzzy-text.js"></script>
  <script src="style-kit/js/effects.js"></script>
</body>
```

## 🎨 Color Palette

| Name        | Hex       | Use                        |
|-------------|-----------|----------------------------|
| Cyan        | `#00D9FF` | Primary accent, glow, links |
| Deep Blue   | `#1E40AF` | Secondary accent, gradients |
| Hot Pink    | `#FF006B` | Tertiary accent, highlights |
| Near Black  | `#0A0A0A` | Card backgrounds            |
| Pure Black  | `#000000` | Page background             |
| White       | `#FFFFFF` | Primary text                |
| Muted       | `#9CA3AF` | Secondary text              |

## ✨ Effects Reference

### Ribbon Cursor
Automatically activates on page load. The cyan trail follows the mouse.
- Configure in `js/cursor-ribbons.js` at the top of the file.

### Fuzzy Text
```html
<canvas class="fuzzy-text" data-text="HELLO WORLD" data-color="#00D9FF" data-size="72"></canvas>
```

### Glass Card
```html
<div class="glass-card">Your content</div>
```

### Gradient Heading
```html
<h1 class="gradient-text">Jake Barton</h1>
```

### Glowing Button
```html
<a href="#" class="btn-primary">View My Work</a>
<a href="#" class="btn-secondary">Get In Touch</a>
```

### Reveal on Scroll
```html
<div class="reveal">This fades in when scrolled into view</div>
```

### Stagger Children
```html
<div class="stagger-children">
  <div>Item 1 - animates first</div>
  <div>Item 2 - animates second</div>
  <div>Item 3 - animates third</div>
</div>
```
