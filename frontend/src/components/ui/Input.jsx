import React from 'react'
import { cn } from './Button'

export const Input = React.forwardRef(({ className, label, error, ...props }, ref) => {
  return (
    <div className="w-full">
      {label && (
        <label className="block text-sm font-medium text-gray-700 mb-1.5">{label}</label>
      )}
      <input
        ref={ref}
        className={cn(
          'w-full px-3 py-2.5 rounded-lg border text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-rac-gold/30 focus:border-rac-gold',
          error ? 'border-rac-red focus:border-rac-red focus:ring-rac-red/30' : 'border-gray-300',
          className
        )}
        {...props}
      />
      {error && <p className="mt-1 text-xs text-rac-red">{error}</p>}
    </div>
  )
})
Input.displayName = 'Input'
