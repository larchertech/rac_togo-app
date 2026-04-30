import React, { useEffect, useState } from 'react'
import { cn } from './Button'

export function StatCard({ title, value, icon: Icon, color = 'gold', badge, suffix = '' }) {
  const [displayValue, setDisplayValue] = useState(0)

  useEffect(() => {
    const duration = 1500
    const steps = 30
    const increment = value / steps
    let current = 0
    let step = 0

    const timer = setInterval(() => {
      step++
      current = Math.min(Math.round(increment * step), value)
      setDisplayValue(current)
      if (step >= steps) clearInterval(timer)
    }, duration / steps)

    return () => clearInterval(timer)
  }, [value])

  const colorClasses = {
    gold: 'bg-rac-gold/10 text-rac-gold border-rac-gold/20',
    green: 'bg-rac-green/10 text-rac-green border-rac-green/20',
    blue: 'bg-rac-blue/10 text-rac-blue border-rac-blue/20',
    red: 'bg-rac-red/10 text-rac-red border-rac-red/20',
  }

  return (
    <div className={cn('rounded-xl border p-5 transition-all hover:shadow-md', colorClasses[color])}>
      <div className="flex items-start justify-between">
        <div>
          <p className="text-sm font-medium opacity-80">{title}</p>
          <p className="font-title text-3xl font-bold mt-2">
            {displayValue.toLocaleString('fr-FR')}{suffix}
          </p>
        </div>
        <div className="p-2 rounded-lg bg-white/50">
          {Icon && <Icon size={20} />}
        </div>
      </div>
      {badge && (
        <div className="mt-3 flex items-center gap-2">
          <span className="w-2 h-2 rounded-full bg-current animate-pulse" />
          <span className="text-xs font-mono uppercase">{badge}</span>
        </div>
      )}
    </div>
  )
}
