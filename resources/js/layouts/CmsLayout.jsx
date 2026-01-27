import React, { useEffect, useState } from 'react';
import { Navigate, Route, Routes, useLocation, useNavigate } from 'react-router-dom';
import { ICONS } from '../components/IconMap';
import { CONTACTS_MENU, CONTENT_MENUS, NAV_MENU, SETTINGS_MENU, USERS_MENU } from '../constants/menus';
import AccessPage from '../pages/AccessPage';
import ContactMessagesPage from '../pages/ContactMessagesPage';
import DashboardPage from '../pages/DashboardPage';
import MenuPage from '../pages/MenuPage';
import SectionPage from '../pages/SectionPage';
import SettingsPage from '../pages/SettingsPage';
import UsersPage from '../pages/UsersPage';

export default function CmsLayout({
    user,
    permissions,
    sections,
    onToggleVisibility,
    onLogout,
    authApi,
    canManageUsers,
    appSettings,
    setAppSettings,
}) {
    const navigate = useNavigate();
    const location = useLocation();
    const canView = (slug) => permissions[slug]?.can_view;
    const [collapsed, setCollapsed] = useState(() => {
        const saved = localStorage.getItem('cms_sidebar_collapsed');
        return saved === 'true';
    });
    const [mobileNavOpen, setMobileNavOpen] = useState(false);
    const [theme, setTheme] = useState(() => {
        return localStorage.getItem('cms_theme') || 'light';
    });
    const [now, setNow] = useState(() => new Date());
    const [adminOpen, setAdminOpen] = useState(false);

    useEffect(() => {
        localStorage.setItem('cms_sidebar_collapsed', String(collapsed));
    }, [collapsed]);

    useEffect(() => {
        localStorage.setItem('cms_theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
    }, [theme]);

    useEffect(() => {
        const timer = setInterval(() => setNow(new Date()), 1000);
        return () => clearInterval(timer);
    }, []);

    useEffect(() => {
        let startX = 0;
        let startY = 0;
        let tracking = false;

        const handleTouchStart = (event) => {
            if (event.touches.length !== 1) return;
            const touch = event.touches[0];
            startX = touch.clientX;
            startY = touch.clientY;
            tracking = true;
        };

        const handleTouchMove = (event) => {
            if (!tracking || event.touches.length !== 1) return;
            const touch = event.touches[0];
            const deltaX = touch.clientX - startX;
            const deltaY = touch.clientY - startY;
            if (Math.abs(deltaY) > 40) {
                tracking = false;
                return;
            }
            if (startX < 24 && deltaX > 60) {
                setMobileNavOpen(true);
                tracking = false;
            } else if (startX > 60 && deltaX < -60) {
                setMobileNavOpen(false);
                tracking = false;
            }
        };

        const handleTouchEnd = () => {
            tracking = false;
        };

        document.addEventListener('touchstart', handleTouchStart, { passive: true });
        document.addEventListener('touchmove', handleTouchMove, { passive: true });
        document.addEventListener('touchend', handleTouchEnd);

        return () => {
            document.removeEventListener('touchstart', handleTouchStart);
            document.removeEventListener('touchmove', handleTouchMove);
            document.removeEventListener('touchend', handleTouchEnd);
        };
    }, []);

    const companyName = appSettings?.company_name || 'Company Profile';

    useEffect(() => {
        const path = location.pathname;
        let pageTitle = 'Dashboard';
        if (path.startsWith('/cms/settings')) {
            pageTitle = 'Settings Website';
        } else if (path.startsWith('/cms/contacts')) {
            pageTitle = 'Contacts';
        } else if (path.startsWith('/cms/menus')) {
            pageTitle = 'Navigation Menu';
        } else if (path.startsWith('/cms/users')) {
            pageTitle = 'Users';
        } else if (path.startsWith('/cms/access')) {
            pageTitle = 'User Access';
        } else if (path.startsWith('/cms/')) {
            const slug = path.replace('/cms/', '').split('/')[0];
            const menu = CONTENT_MENUS.find((item) => item.slug === slug);
            pageTitle = menu ? menu.label : 'CMS';
        }
        document.title = `${companyName} - ${pageTitle}`;
    }, [companyName, location.pathname]);

    const handleNavigate = (path) => {
        navigate(path);
        setMobileNavOpen(false);
    };

    return (
        <div className={`cms ${collapsed ? 'collapsed' : ''} ${mobileNavOpen ? 'mobile-open' : ''}`}>
            <aside className="sidebar">
                <div className="brand">
                    <span className="brand-icon">
                        {ICONS.dashboard}
                    </span>
                    <span className="brand-text">{companyName} CMS</span>
                </div>
                <nav>
                    <button
                        className="nav-item"
                        onClick={() => handleNavigate('/dashboard')}
                    >
                        <span className="icon">{ICONS.dashboard}</span>
                        <span className="label">Dashboard</span>
                    </button>
                    {CONTENT_MENUS.filter((item) => canView(item.slug)).map((item) => (
                        <button
                            key={item.slug}
                            className="nav-item"
                            onClick={() => handleNavigate(`/cms/${item.slug}`)}
                        >
                            <span className="icon">{ICONS[item.slug] || ICONS.dashboard}</span>
                            <span className="label">{item.label}</span>
                        </button>
                    ))}
                    {canView(SETTINGS_MENU.slug) && (
                        <button
                            className="nav-item"
                            onClick={() => handleNavigate('/cms/settings')}
                        >
                            <span className="icon">{ICONS.settings}</span>
                            <span className="label">{SETTINGS_MENU.label}</span>
                        </button>
                    )}
                    {canView(CONTACTS_MENU.slug) && (
                        <button
                            className="nav-item"
                            onClick={() => handleNavigate('/cms/contacts')}
                        >
                            <span className="icon">{ICONS.contacts}</span>
                            <span className="label">{CONTACTS_MENU.label}</span>
                        </button>
                    )}
                    {canView(NAV_MENU.slug) && (
                        <button
                            className="nav-item"
                            onClick={() => handleNavigate('/cms/menus')}
                        >
                            <span className="icon">{ICONS.menus}</span>
                            <span className="label">{NAV_MENU.label}</span>
                        </button>
                    )}
                    {user?.role === 'super_admin' && (canView(USERS_MENU.slug) || canManageUsers) && (
                        <div className="nav-group">
                            <button
                                className="nav-item nav-parent"
                                type="button"
                                onClick={() => setAdminOpen((prev) => !prev)}
                            >
                                <span className="icon">{ICONS.access}</span>
                                <span className="label">Administrator</span>
                                <span className="nav-caret">
                                    <i className={`fa-solid fa-chevron-${adminOpen ? 'up' : 'down'}`}></i>
                                </span>
                            </button>
                            <div className={`nav-submenu ${adminOpen ? 'open' : ''}`}>
                                {canView(USERS_MENU.slug) && (
                                    <button
                                        className="nav-item nav-child"
                                        onClick={() => handleNavigate('/cms/users')}
                                    >
                                        <span className="icon">{ICONS.users}</span>
                                        <span className="label">{USERS_MENU.label}</span>
                                    </button>
                                )}
                                {canManageUsers && (
                                    <button
                                        className="nav-item nav-child"
                                        onClick={() => handleNavigate('/cms/access')}
                                    >
                                        <span className="icon">{ICONS.access}</span>
                                        <span className="label">User Access</span>
                                    </button>
                                )}
                            </div>
                        </div>
                    )}
                </nav>
            </aside>
            <button
                className="sidebar-overlay"
                type="button"
                aria-label="Close menu"
                onClick={() => setMobileNavOpen(false)}
            />
            <div className="main">
                <header className="topbar">
                    <div className="topbar-left">
                        <button
                            className="ghost icon-button mobile-toggle"
                            onClick={() => setMobileNavOpen((prev) => !prev)}
                            title="Toggle menu"
                        >
                            {ICONS.collapse}
                        </button>
                        <button
                            className="ghost icon-button desktop-only"
                            onClick={() => setCollapsed((prev) => !prev)}
                            title="Toggle sidebar"
                        >
                            {ICONS.collapse}
                        </button>
                        <div className="topbar-brand">
                            <span className="subtle">
                                {now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                                {' '}•{' '}
                                {now.toLocaleTimeString('id-ID')}
                            </span>
                        </div>
                    </div>
                    <div className="dropdown">
                        <button
                            className="btn btn-light dropdown-toggle profile-btn"
                            data-bs-toggle="dropdown"
                            type="button"
                        >
                            <i className="fa-solid fa-circle-user"></i>
                            <span className="label">{user?.name}</span>
                        </button>
                        <ul className="dropdown-menu dropdown-menu-end">
                            <li>
                                <button className="dropdown-item" type="button">
                                    <i className="fa-regular fa-user"></i> Profile
                                </button>
                            </li>
                            <li>
                                <button
                                    className="dropdown-item"
                                    type="button"
                                    onClick={() => setTheme((prev) => (prev === 'dark' ? 'light' : 'dark'))}
                                >
                                    {theme === 'dark' ? (
                                        <>
                                            <i className="fa-solid fa-sun"></i> Light Mode
                                        </>
                                    ) : (
                                        <>
                                            <i className="fa-solid fa-moon"></i> Dark Mode
                                        </>
                                    )}
                                </button>
                            </li>
                            <li><hr className="dropdown-divider" /></li>
                            <li>
                                <button className="dropdown-item text-danger" onClick={onLogout}>
                                    <i className="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </header>
                <div className="main-body">
                    <Routes>
                        <Route path="/dashboard" element={<DashboardPage user={user} onLogout={onLogout} authApi={authApi} />} />
                        <Route
                            path="/cms/:slug"
                            element={(
                                <SectionPage
                                    permissions={permissions}
                                    sections={sections}
                                    onToggleVisibility={onToggleVisibility}
                                    authApi={authApi}
                                />
                            )}
                        />
                        <Route
                            path="/cms/settings"
                            element={<SettingsPage authApi={authApi} onSettingsUpdated={setAppSettings} />}
                        />
                        <Route path="/cms/contacts" element={<ContactMessagesPage authApi={authApi} />} />
                        <Route path="/cms/menus" element={<MenuPage authApi={authApi} />} />
                        <Route path="/cms/users" element={<UsersPage authApi={authApi} />} />
                        <Route path="/cms/access" element={<AccessPage authApi={authApi} />} />
                        <Route path="*" element={<Navigate to="/dashboard" replace />} />
                    </Routes>
                </div>
                <footer className="cms-footer">
                    <span>Powered by CMS Company</span>
                    <span>© {new Date().getFullYear()}</span>
                </footer>
            </div>
        </div>
    );
}
