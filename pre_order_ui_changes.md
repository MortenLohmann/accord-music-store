# Pre-Order UI Changes Visualization

## Før (Before)

```mermaid
graph TD
    subgraph Single_Column_Layout[En-kolonne Layout]
        Header[Produkt Header]
        Image[Produkt Billede]
        Title[Titel]
        Price[Pris]
        Description[Beskrivelse]
        Button[Pre-order Knap]
        Details[Produktdetaljer]
    end

    Header --> Image
    Image --> Title
    Title --> Price
    Price --> Description
    Description --> Button
    Button --> Details

    classDef header fill:#f9f9f9,stroke:#333,stroke-width:2px
    classDef content fill:#fff,stroke:#666
    classDef button fill:#ddd,stroke:#333
    
    class Header,Image header
    class Title,Price,Description content
    class Button button
```

## Efter (After)

```mermaid
graph TD
    subgraph Two_Column_Layout[To-kolonne Layout]
        subgraph Left_Column[Venstre Kolonne]
            Header2[Produkt Header]
            Image2[Stort Produkt Billede]
            Title2[Fremhævet Titel]
            Description2[Formateret Beskrivelse]
            TrackList[Track List]
        end

        subgraph Right_Column[Højre Kolonne]
            OrderSummary[Ordre Resumé]
            DeliveryDate[Estimeret Levering]
            PriceBox[Pris + Rabat]
            Shipping[Leveringsmetode]
            CTAButton[Fremhævet CTA Knap]
            Trust[Tillidsmarkører]
        end
    end

    Header2 --> Image2
    Image2 --> Title2
    Title2 --> Description2
    Description2 --> TrackList

    OrderSummary --> DeliveryDate
    DeliveryDate --> PriceBox
    PriceBox --> Shipping
    Shipping --> CTAButton
    CTAButton --> Trust

    classDef header fill:#f0f0f0,stroke:#333,stroke-width:2px
    classDef content fill:#fff,stroke:#666
    classDef cta fill:#4CAF50,stroke:#45a049,color:#fff
    classDef trust fill:#fff8dc,stroke:#deb887
    
    class Header2 header
    class Image2,Title2,Description2,TrackList content
    class CTAButton cta
    class Trust trust
```

## Forbedrede UI Elementer (Enhanced UI Elements)

```mermaid
graph LR
    subgraph New_Features[Nye UI Elementer]
        Status[Status Indikator]
        Timer[Nedtællingstimer]
        Social[Social Proof]
        Stock[Lager Status]
    end

    subgraph Enhanced_Elements[Forbedrede Elementer]
        CTA[Optimeret CTA]
        Images[HD Billeder]
        Typography[Forbedret Typografi]
        Spacing[Optimeret Whitespace]
    end

    Status --> CTA
    Timer --> Images
    Social --> Typography
    Stock --> Spacing

    classDef new fill:#e6f3ff,stroke:#3182ce
    classDef enhanced fill:#f0fff4,stroke:#38a169
    
    class Status,Timer,Social,Stock new
    class CTA,Images,Typography,Spacing enhanced
```

## Brugerrejse (User Journey)

```mermaid
graph LR
    Start[Landing] -->|Se Produkt| Info[Product Info]
    Info -->|Scroll| Details[Se Detaljer]
    Details -->|Interesse| Price[Check Pris]
    Price -->|Beslutning| CTA[Klik CTA]
    CTA -->|Udfyld| Form[Ordre Form]
    Form -->|Bekræft| Complete[Ordre Komplet]

    classDef journey fill:#f8f9fa,stroke:#343a40
    class Start,Info,Details,Price,CTA,Form,Complete journey
```

## Mobile Responsivt Design (Mobile Responsive Design)

```mermaid
graph TD
    subgraph Desktop[Desktop Version]
        D_Left[Venstre Kolonne] --- D_Right[Højre Kolonne]
    end

    subgraph Mobile[Mobil Version]
        M_Top[Produkt Sektion]
        M_Middle[Info Sektion]
        M_Bottom[Ordre Sektion]
    end

    Desktop --- Mobile

    classDef desktop fill:#f5f5f5,stroke:#333
    classDef mobile fill:#e9ecef,stroke:#495057
    
    class D_Left,D_Right desktop
    class M_Top,M_Middle,M_Bottom mobile
``` 