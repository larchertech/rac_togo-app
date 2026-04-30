import React from 'react'
import { cn } from './Button'

export function ProgressBar({ current, total, label, className }) {
  const percentage = total > 0 ? (current / total) * 100 : 0

  return (
    <div className={cn('w-full', className)}>
      <div className="flex justify-between text-sm mb-1.5">
        <span className="text-gray-600">{label}</span>
        <span className="font-mono text-rac-gold-dark">{current} / {total}</span>
      </div>
      <div className="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
        <div
          className="h-full bg-rac-gold rounded-full transition-all duration-500 ease-out"
          style={{ width: `${percentage}%` }}
        />
      </div>
    </div>
  )
}
