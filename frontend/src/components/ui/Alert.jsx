import React from 'react'
import { AlertTriangle, CheckCircle, Info, XCircle } from 'lucide-react'
import { cn } from './Button'

export function Alert({ children, variant = 'info', className }) {
  const icons = {
    info: <Info size={18} className="text-rac-blue shrink-0" />,
    success: <CheckCircle size={18} className="text-rac-green shrink-0" />,
    warning: <AlertTriangle size={18} className="text-rac-gold shrink-0" />,
    danger: <XCircle size={18} className="text-rac-red shrink-0" />,
  }

  const styles = {
    info: 'bg-rac-blue/10 border-rac-blue/20 text-rac-blue',
    success: 'bg-rac-green/10 border-rac-green/20 text-rac-green',
    warning: 'bg-rac-gold/10 border-rac-gold/20 text-rac-gold-dark',
    danger: 'bg-rac-red/10 border-rac-red/20 text-rac-red',
  }

  return (
    <div className={cn('flex items-start gap-3 p-4 rounded-lg border', styles[variant], className)}>
      {icons[variant]}
      <div className="text-sm">{children}</div>
    </div>
  )
}
