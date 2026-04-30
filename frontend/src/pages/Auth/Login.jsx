import React, { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '../../store/authStore'
import { Button } from '../../components/ui/Button'
import { Input } from '../../components/ui/Input'
import { Select } from '../../components/ui/Select'
import { Badge } from '../../components/ui/Badge'
import { Loader2, MessageCircle, Mail, Smartphone } from 'lucide-react'

export default function Login() {
  const navigate = useNavigate()
  const { sendOtp, verifyOtp, isLoading } = useAuthStore()
  const [step, setStep] = useState('phone')
  const [phone, setPhone] = useState('')
  const [canal, setCanal] = useState('whatsapp')
  const [otp, setOtp] = useState(['', '', '', '', '', ''])
  const [error, setError] = useState('')
  const [countdown, setCountdown] = useState(600)

  const canalOptions = [
    { value: 'whatsapp', label: 'WhatsApp' },
    { value: 'email', label: 'Email' },
    { value: 'sms', label: 'SMS' },
  ]

  const handleSendOtp = async (e) => {
    e.preventDefault()
    setError('')
    const result = await sendOtp(phone, canal)
    if (result.success) {
      setStep('otp')
      setCountdown(600)
      const timer = setInterval(() => {
        setCountdown((prev) => {
          if (prev <= 1) clearInterval(timer)
          return prev - 1
        })
      }, 1000)
    } else {
      setError(result.message)
    }
  }

  const handleVerifyOtp = async (e) => {
    e.preventDefault()
    setError('')
    const code = otp.join('')
    const result = await verifyOtp(phone, code)
    if (result.success) {
      navigate('/')
    } else {
      setError(result.message)
    }
  }

  const handleOtpChange = (index, value) => {
    if (value.length > 1) return
    const newOtp = [...otp]
    newOtp[index] = value
    setOtp(newOtp)
    if (value && index < 5) {
      document.getElementById(`otp-${index + 1}`)?.focus()
    }
  }

  const formatTime = (seconds) => {
    const m = Math.floor(seconds / 60)
    const s = seconds % 60
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`
  }

  return (
    <div className="min-h-screen bg-rac-dark kente-pattern flex items-center justify-center p-4">
      <div className="w-full max-w-md">
        {/* Logo */}
        <div className="text-center mb-8">
          <div className="w-16 h-16 bg-rac-gold rounded-full flex items-center justify-center mx-auto mb-4">
            <span className="text-rac-dark font-title font-bold text-2xl">RAC</span>
          </div>
          <h1 className="font-title text-3xl font-bold text-rac-gold">RAC-TOGO</h1>
          <p className="text-white/60 text-sm mt-2">Gestion des Alumni & Processus Électoral</p>
        </div>

        <div className="bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 p-6 md:p-8">
          {step === 'phone' ? (
            <form onSubmit={handleSendOtp} className="space-y-5">
              <h2 className="font-title text-xl font-semibold text-white text-center">Connexion</h2>
              <div>
                <label className="block text-sm text-white/80 mb-2">Numéro WhatsApp</label>
                <Input
                  type="tel"
                  placeholder="+228 XX XX XX XX"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  className="bg-white/10 border-white/20 text-white placeholder:text-white/40"
                  required
                />
              </div>
              <div>
                <label className="block text-sm text-white/80 mb-2">Canal de réception</label>
                <Select
                  value={canal}
                  onChange={(e) => setCanal(e.target.value)}
                  options={canalOptions}
                  className="bg-white/10 border-white/20 text-white"
                />
              </div>
              {error && (
                <div className="p-3 bg-rac-red/10 border border-rac-red/30 rounded-lg">
                  <p className="text-sm text-rac-red">{error}</p>
                </div>
              )}
              <Button
                type="submit"
                variant="primary"
                className="w-full"
                disabled={isLoading}
              >
                {isLoading ? (
                  <Loader2 className="animate-spin" size={18} />
                ) : (
                  <>
                    <MessageCircle size={18} />
                    Recevoir mon code
                  </>
                )}
              </Button>
            </form>
          ) : (
            <form onSubmit={handleVerifyOtp} className="space-y-5">
              <h2 className="font-title text-xl font-semibold text-white text-center">Vérification</h2>
              <p className="text-center text-white/60 text-sm">
                Code envoyé sur {canal} au{' '}
                <span className="text-rac-gold font-mono">{phone.substring(0, 7)}****</span>
              </p>
              <div className="flex justify-center gap-2">
                {otp.map((digit, i) => (
                  <input
                    key={i}
                    id={`otp-${i}`}
                    type="text"
                    inputMode="numeric"
                    maxLength={1}
                    value={digit}
                    onChange={(e) => handleOtpChange(i, e.target.value)}
                    className="w-12 h-14 text-center text-xl font-mono bg-white/10 border border-white/20 rounded-lg text-white focus:border-rac-gold focus:outline-none focus:ring-2 focus:ring-rac-gold/30"
                    required
                  />
                ))}
              </div>
              <div className="text-center">
                <span className="font-mono text-rac-gold text-sm">{formatTime(countdown)}</span>
              </div>
              {error && (
                <div className="p-3 bg-rac-red/10 border border-rac-red/30 rounded-lg">
                  <p className="text-sm text-rac-red">{error}</p>
                </div>
              )}
              <Button type="submit" variant="primary" className="w-full" disabled={isLoading}>
                {isLoading ? <Loader2 className="animate-spin" size={18} /> : 'Valider'}
              </Button>
              <button
                type="button"
                onClick={() => setStep('phone')}
                className="w-full text-center text-sm text-white/50 hover:text-white transition-colors"
              >
                Modifier le numéro
              </button>
            </form>
          )}
        </div>

        <div className="text-center mt-6">
          <p className="text-white/40 text-xs">
            Compassion International Togo — {new Date().getFullYear()}
          </p>
        </div>
      </div>
    </div>
  )
}
