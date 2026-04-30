import React from 'react'
import { cn } from './Button'

export function Timeline({ items }) {
  return (
    <div className="relative space-y-4">
      <div className="absolute left-3.5 top-2 bottom-2 w-px bg-gray-200" />
      {items.map((item, i) => (
        <div key={i} className="relative flex items-start gap-4 pl-1">
          <div className={cn(
            'relative z-10 w-3 h-3 rounded-full mt-1.5 shrink-0',
            item.variant === 'success' ? 'bg-rac-green' :
            item.variant === 'warning' ? 'bg-rac-gold' :
            item.variant === 'danger' ? 'bg-rac-red' :
            'bg-gray-300'
          )} />
          <div className="flex-1 pb-4">
            <div className="flex items-center justify-between">
              <p className="text-sm font-medium text-gray-900">{item.title}</p>
              <span className="text-xs text-gray-400 font-mono">{item.date}</span>
            </div>
            <p className="text-sm text-gray-600 mt-0.5">{item.description}</p>
          </div>
        </div>
      ))}
    </div>
  )
}
