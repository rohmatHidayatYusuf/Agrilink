<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'AgriLink — Sistem Pertanian Terpadu') ?></title>
  <meta name="description" content="Platform terintegrasi pengelolaan usaha pertanian AgriLink.">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- AgriLink Design System CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">

  <style>
    /* Styling Tambahan Komponen Auth & Formulir AgriLink */
    .auth-page-wrapper {
      min-height: 100vh;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 50%, #ecfdf5 100%);
      padding: 2rem 1rem;
    }

    .auth-card {
      background: #ffffff;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-xl);
      max-width: 520px;
      width: 100%;
      padding: 2.5rem 2.25rem;
      box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      position: relative;
    }

    .auth-card.auth-card-wide {
      max-width: 620px;
    }

    .auth-header {
      text-align: center;
      margin-bottom: 1.75rem;
    }

    .auth-logo {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 0.85rem;
      text-decoration: none;
    }

    .auth-header h1 {
      font-size: 1.35rem;
      color: var(--slate-900);
      margin-bottom: 0.35rem;
    }

    .auth-header p {
      font-size: 0.875rem;
      color: var(--slate-500);
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    @media (max-width: 600px) {
      .form-row {
        grid-template-columns: 1fr;
        gap: 0;
      }
    }

    .form-label {
      display: block;
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--slate-700);
      margin-bottom: 0.4rem;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 0.85rem;
      color: var(--slate-400);
      pointer-events: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-control {
      width: 100%;
      padding: 0.68rem 0.95rem;
      font-size: 0.875rem;
      font-family: var(--font-sans);
      color: var(--slate-800);
      background-color: #ffffff;
      border: 1px solid var(--slate-300);
      border-radius: var(--radius-md);
      transition: all var(--transition-fast);
      outline: none;
    }

    .has-icon .form-control {
      padding-left: 2.5rem;
    }

    .has-toggle .form-control {
      padding-right: 2.5rem;
    }

    .form-control:focus {
      border-color: var(--primary-600);
      box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
    }

    .form-control.is-invalid {
      border-color: #ef4444;
      background-color: #fef2f2;
    }

    .form-control.is-invalid:focus {
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }

    .invalid-feedback {
      display: block;
      color: #dc2626;
      font-size: 0.75rem;
      margin-top: 0.35rem;
      font-weight: 500;
    }

    .password-toggle-btn {
      position: absolute;
      right: 0.75rem;
      background: none;
      border: none;
      color: var(--slate-400);
      cursor: pointer;
      padding: 0.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--radius-sm);
    }

    .password-toggle-btn:hover {
      color: var(--slate-700);
    }

    /* Buttons */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.68rem 1.25rem;
      font-size: 0.875rem;
      font-weight: 600;
      font-family: var(--font-sans);
      border-radius: var(--radius-md);
      border: 1px solid transparent;
      cursor: pointer;
      transition: all var(--transition-fast);
      text-decoration: none;
      line-height: 1.25;
    }

    .btn-primary {
      background-color: var(--primary-700);
      color: #ffffff;
      box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
    }

    .btn-primary:hover {
      background-color: var(--primary-800);
      color: #ffffff;
      box-shadow: var(--shadow-sm);
    }

    .btn-primary:active {
      background-color: var(--primary-900);
    }

    .btn-outline {
      background-color: #ffffff;
      border-color: var(--slate-300);
      color: var(--slate-700);
    }

    .btn-outline:hover {
      background-color: var(--slate-50);
      border-color: var(--slate-400);
      color: var(--slate-900);
    }

    .btn-block {
      width: 100%;
    }

    /* Role Selection Grid */
    .role-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.75rem;
      margin-bottom: 1.25rem;
    }

    @media (max-width: 480px) {
      .role-grid {
        grid-template-columns: 1fr;
      }
    }

    .role-option {
      position: relative;
    }

    .role-option input[type="radio"] {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }

    .role-card-label {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 0.85rem 0.5rem;
      border: 1.5px solid var(--slate-200);
      border-radius: var(--radius-lg);
      background: #ffffff;
      cursor: pointer;
      transition: all var(--transition-fast);
      gap: 0.4rem;
    }

    .role-card-label:hover {
      border-color: var(--primary-400);
      background: var(--primary-50);
    }

    .role-option input[type="radio"]:checked + .role-card-label {
      border-color: var(--primary-600);
      background: var(--primary-50);
      box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }

    .role-card-label .role-title {
      font-weight: 700;
      font-size: 0.82rem;
      color: var(--slate-800);
    }

    .role-card-label .role-desc {
      font-size: 0.68rem;
      color: var(--slate-500);
    }

    /* Alerts */
    .alert {
      padding: 0.85rem 1rem;
      border-radius: var(--radius-md);
      font-size: 0.84rem;
      display: flex;
      align-items: flex-start;
      gap: 0.65rem;
      margin-bottom: 1.25rem;
      line-height: 1.4;
    }

    .alert-danger {
      background-color: var(--danger-bg);
      border: 1px solid var(--danger-border);
      color: var(--danger-text);
    }

    .alert-success {
      background-color: var(--success-bg);
      border: 1px solid var(--success-border);
      color: var(--success-text);
    }

    .alert-info {
      background-color: var(--info-bg);
      border: 1px solid var(--info-border);
      color: var(--info-text);
    }

    /* Demo Persona Grid */
    .demo-persona-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.6rem;
      margin-top: 0.75rem;
    }

    .demo-persona-btn {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.65rem 0.75rem;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-md);
      background: var(--slate-50);
      color: var(--slate-700);
      text-decoration: none;
      transition: all var(--transition-fast);
      text-align: left;
    }

    .demo-persona-btn:hover {
      border-color: var(--primary-600);
      background: var(--primary-50);
      color: var(--primary-900);
      transform: translateY(-1px);
      box-shadow: var(--shadow-xs);
    }

    .demo-persona-badge {
      font-size: 0.65rem;
      font-weight: 700;
      text-transform: uppercase;
      padding: 0.15rem 0.4rem;
      border-radius: var(--radius-xs);
      background: #ffffff;
      border: 1px solid var(--slate-300);
    }

    .divider-text {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
      color: var(--slate-400);
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .divider-text::before,
    .divider-text::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid var(--slate-200);
    }

    .divider-text::before { margin-right: 0.75rem; }
    .divider-text::after { margin-left: 0.75rem; }
  </style>
</head>
<body>