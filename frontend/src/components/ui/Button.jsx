import React from 'react'
import { clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

export function cn(...inputs) {
  return twMerge(clsx(inputs))
}

export function Button({ children, variant = 'primary', size = 'md', className, disabled, ...props }) {
  return (
    <button
      className={cn(
        'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
        {
          'bg-rac-gold text-rac-dark hover:bg-rac-gold-dark focus:ring-rac-gold': variant === 'primary',
          'bg-rac-dark text-white hover:bg-rac-dark-light focus:ring-rac-dark': variant === 'dark',
          'bg-rac-green text-white hover:bg-rac-green-light focus:ring-rac-green': variant === 'success',
          'bg-rac-red text-white hover:bg-red-700 focus:ring-rac-red': variant === 'danger',
          'bg-rac-blue text-white hover:bg-blue-700 focus:ring-rac-blue': variant === 'info',
          'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-300': variant === 'outline',
          'bg-transparent text-gray-600 hover:bg-gray-100': variant === 'ghost',
          'px-3 py-1.5 text-sm': size === 'sm',
          'px-4 py-2 text-sm': size === 'md',
          'px-6 py-3 text-base': size === 'lg',
        },
        className
      )}
      disabled={disabled}
      {...props}
    >
      {children}
    </button>
  )
}
