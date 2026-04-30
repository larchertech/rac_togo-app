import React from 'react'
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts'

export default function ClusterBarChart({ data }) {
  const top8 = data?.slice(0, 8) || []
  return (
    <ResponsiveContainer width="100%" height={280}>
      <BarChart data={top8} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
        <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
        <XAxis dataKey="nom" tick={{ fontSize: 11 }} angle={-25} textAnchor="end" height={60} />
        <YAxis tick={{ fontSize: 11 }} />
        <Tooltip
          contentStyle={{ borderRadius: 8, border: 'none', boxShadow: '0 4px 12px rgba(0,0,0,0.1)' }}
          formatter={(value) => [value, 'Alumni']}
        />
        <Bar dataKey="total" radius={[4, 4, 0, 0]}>
          {top8.map((entry, index) => (
            <Cell key={`cell-${index}`} fill={entry.nom?.includes('Lomé') ? '#C8A45C' : '#2D6A4F'} />
          ))}
        </Bar>
      </BarChart>
    </ResponsiveContainer>
  )
}
