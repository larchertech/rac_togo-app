import React from 'react'
import { QRCodeSVG } from 'qrcode.react'
import { Card, CardContent } from './Card'
import { Download } from 'lucide-react'
import { Button } from './Button'

export function QRCard({ data, title = 'QR Code' }) {
  const qrValue = typeof data === 'string' ? data : JSON.stringify(data)

  return (
    <Card className="bg-rac-dark text-white overflow-hidden relative">
      <div className="absolute inset-0 kente-pattern opacity-5" />
      <CardContent className="p-6 relative">
        <h3 className="font-title text-lg font-bold text-rac-gold mb-4">{title}</h3>
        <div className="flex items-center justify-between">
          <QRCodeSVG
            value={qrValue}
            size={120}
            level="M"
            bgColor="transparent"
            fgColor="#C8A45C"
          />
          <Button variant="outline" size="sm" className="border-white/30 text-white hover:bg-white/10 gap-2">
            <Download size={14} /> Télécharger
          </Button>
        </div>
      </CardContent>
    </Card>
  )
}
