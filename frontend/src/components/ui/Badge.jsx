import React from 'react'
import { cn } from './Button'

export function Badge({ children, variant = 'default', className }) {
  return (
    <span
      className={cn(
        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
        {
          'bg-gray-100 text-gray-800': variant === 'default',
          'bg-rac-green/10 text-rac-green': variant === 'success',
          'bg-rac-gold/10 text-rac-gold-dark': variant === 'warning',
          'bg-rac-red/10 text-rac-red': variant === 'danger',
          'bg-rac-blue/10 text-rac-blue': variant === 'info',
          'bg-rac-dark/10 text-rac-dark': variant === 'dark',
        },
        className
      )}
    >
      {children}
    </span>
  )
}
