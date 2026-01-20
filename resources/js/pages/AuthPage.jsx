import React, { useEffect, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import Swal from 'sweetalert2';
import api from '../api/client';
import { swalDefaults } from '../utils/swal';

export default function AuthPage({ mode, onLogin }) {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const location = useLocation();
    const navigate = useNavigate();

    useEffect(() => {
        setPassword('');
    }, [mode]);

    useEffect(() => {
        const title = mode === 'register' ? 'Register' : 'Login';
        document.title = `${title} - Company Profile`;
    }, [location.pathname, mode]);

    const handleAuth = async (e) => {
        e.preventDefault();
        try {
            const res = await api.post('/auth/login', { email, password });
            onLogin(res.data.token, res.data.user);
            navigate('/dashboard', { replace: true });
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Login gagal.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    return (
        <div className="auth">
            <main className="auth-card">
                <div className="auth-header">
                    <div className="auth-badge">
                        <i className="fa-solid fa-shield-halved"></i>
                        Secure CMS
                    </div>
                    <h1>{mode === 'register' ? 'Buat Akun' : 'Masuk'}</h1>
                    <p className="muted">
                        Masuk untuk mengelola company profile.
                    </p>
                </div>
                <form onSubmit={handleAuth} className="auth-form">
                    <label className="field">
                        <span>Email</span>
                        <div className="input-wrap">
                            <i className="fa-regular fa-envelope"></i>
                            <input
                                type="email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                placeholder="nama@email.com"
                                required
                            />
                        </div>
                    </label>
                    <label className="field">
                        <span>Password</span>
                        <div className="input-wrap">
                            <i className="fa-solid fa-lock"></i>
                            <input
                                type="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                placeholder="Minimal 8 karakter"
                                required
                            />
                        </div>
                    </label>
                    <button type="submit" className="btn-primary">
                        Masuk
                    </button>
                    <p className="helper">Hubungi admin untuk mendapatkan akun.</p>
                </form>
            </main>
        </div>
    );
}
